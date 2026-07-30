<?php
namespace App\Controllers;
use App\Core\Controller;
use App\Core\Auth;
use App\Core\Session;
use App\Models\GiftCard;
use App\Models\User;
use App\Models\WalletTransaction;

class GiftCardController extends Controller
{
    public function index(): void
    {
        $db = \App\Core\Database::getInstance();
        
        // Fetch public/system-issued active gift cards
        $publicCards = $db->query(
            "SELECT * FROM gift_cards 
             WHERE purchased_by IS NULL 
               AND status = 'active' 
             ORDER BY created_at DESC"
        )->fetchAll();

        // Fetch user's purchased cards
        $myPurchased = [];
        if (Auth::check()) {
            $myPurchased = $db->query(
                "SELECT * FROM gift_cards 
                 WHERE purchased_by = ? 
                 ORDER BY created_at DESC",
                [Auth::id()]
            )->fetchAll();
        }

        $this->render('giftcards/index', [
            'pageTitle' => 'Gift Cards — ShopX Global',
            'wallet' => Auth::check() ? Auth::wallet() : 0,
            'publicCards' => $publicCards,
            'myPurchased' => $myPurchased,
        ]);
    }
    
    public function purchase(): void
    {
        $this->validateCsrf();
        if (!Auth::check()) {
            Session::flash('error', 'Please log in to purchase gift cards.');
            $this->redirect('/gift-cards');
            return;
        }

        $id = (int) $this->input('gift_card_id', 0);
        $gc = GiftCard::find($id);
        
        if (!$gc || $gc['purchased_by'] !== null || $gc['status'] !== 'active') {
            Session::flash('error', 'This gift card is not available for purchase.');
            $this->redirect('/gift-cards');
            return;
        }
        
        $amount = (float) $gc['initial_value'];
        
        Auth::refreshWallet();
        if (Auth::wallet() < $amount) {
            Session::flash('error', 'Insufficient wallet balance. Please add funds first.');
            $this->redirect('/gift-cards');
            return;
        }
        
        // Update the gift card to mark it as purchased
        GiftCard::update($id, [
            'purchased_by' => Auth::id()
        ]);
        
        User::updateWallet(Auth::id(), -$amount);
        WalletTransaction::create([
            'user_id' => Auth::id(), 
            'type' => 'purchase',
            'amount' => -$amount, 
            'description' => "Gift card purchase: {$gc['code']} (" . ($gc['name'] ?: 'Gift Card') . ")",
        ]);
        Auth::refreshWallet();
        
        // Dispatch gift card purchased email
        if (Auth::email()) {
            \App\Core\Mailer::sendTriggerEmail('gift_card_purchased', Auth::email(), [
                'name' => Auth::name(),
                'gift_card_code' => $gc['code'],
                'amount' => formatPrice($amount),
                'name_on_card' => $gc['name'] ?: 'ShopX Gift Card'
            ], Auth::name());
        }
        
        Session::flash('success', "Gift card purchased successfully! Code: {$gc['code']}");
        $this->redirect('/gift-cards');
    }
    
    public function redeem(): void
    {
        $this->validateCsrf();
        $code = strtoupper(trim($this->input('code', '')));
        
        $gc = GiftCard::findByCode($code);
        if (!$gc || $gc['status'] !== 'active' || $gc['remaining_value'] <= 0) {
            Session::flash('error', 'Invalid or already used gift card code.');
            $this->redirect('/gift-cards');
            return;
        }
        
        $amount = $gc['remaining_value'];
        User::updateWallet(Auth::id(), $amount);
        GiftCard::update($gc['id'], [
            'remaining_value' => 0,
            'redeemed_by' => Auth::id(),
            'status' => 'used',
        ]);
        WalletTransaction::create([
            'user_id' => Auth::id(), 'type' => 'gift_card',
            'amount' => $amount, 'description' => "Gift card redeemed: $code",
        ]);
        Auth::refreshWallet();
        
        // Dispatch gift card redeemed email
        if (Auth::email()) {
            \App\Core\Mailer::sendTriggerEmail('gift_card_redeemed', Auth::email(), [
                'name' => Auth::name(),
                'gift_card_code' => $code,
                'amount' => formatPrice($amount),
                'new_wallet_balance' => formatPrice(Auth::wallet())
            ], Auth::name());
        }
        
        Session::flash('success', "Gift card redeemed! " . formatPrice($amount) . " added to your wallet.");
        $this->redirect('/gift-cards');
    }
}
