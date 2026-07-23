<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Session;
use App\Models\GiftCard;

class AdminGiftCardController extends Controller
{
    public function index(): void
    {
        $db = \App\Core\Database::getInstance();
        
        // Self-healing DB migration for 'name' column
        try {
            $db->query("SELECT name FROM gift_cards LIMIT 1");
        } catch (\PDOException $e) {
            $db->query("ALTER TABLE gift_cards ADD COLUMN name VARCHAR(100) DEFAULT NULL AFTER code");
        }

        $giftCards = $db->query(
            "SELECT gc.*, 
                    u_pb.name as buyer_name, 
                    u_rb.name as redeemer_name 
             FROM gift_cards gc 
             LEFT JOIN users u_pb ON gc.purchased_by = u_pb.id 
             LEFT JOIN users u_rb ON gc.redeemed_by = u_rb.id 
             ORDER BY gc.created_at DESC"
        )->fetchAll();

        $this->render('admin/giftcards/index', [
            'pageTitle' => 'Gift Card Operations',
            'giftCards' => $giftCards,
        ], 'admin');
    }

    public function issue(): void
    {
        $this->validateCsrf();
        $amount = (float) $this->input('amount', 0);
        $name = trim($this->input('name', ''));
        $code = trim($this->input('code', ''));
        
        if ($amount <= 0) {
            Session::flash('error', 'Please enter a valid amount greater than $0.');
            $this->redirect('/admin/gift-cards');
            return;
        }

        if (empty($code)) {
            $code = GiftCard::generateUniqueCode();
        } else {
            // Check if code is unique
            if (GiftCard::findByCode($code)) {
                Session::flash('error', 'This gift card code already exists. Please choose a unique code.');
                $this->redirect('/admin/gift-cards');
                return;
            }
        }

        GiftCard::create([
            'code' => $code,
            'name' => $name ?: null,
            'initial_value' => $amount,
            'remaining_value' => $amount,
            'status' => 'active',
        ]);

        Session::flash('success', "Gift Card code: $code issued successfully!");
        $this->redirect('/admin/gift-cards');
    }

    public function void(string $id): void
    {
        $this->validateCsrf();
        $gc = GiftCard::find((int) $id);
        if (!$gc) {
            Session::flash('error', 'Gift Card not found.');
            $this->redirect('/admin/gift-cards');
            return;
        }

        GiftCard::update((int) $id, ['status' => 'voided']);
        Session::flash('success', 'Gift Card voided successfully.');
        $this->redirect('/admin/gift-cards');
    }

    public function delete(string $id): void
    {
        $this->validateCsrf();
        $gc = GiftCard::find((int) $id);
        if (!$gc) {
            Session::flash('error', 'Gift Card not found.');
            $this->redirect('/admin/gift-cards');
            return;
        }

        GiftCard::delete((int) $id);
        Session::flash('success', 'Gift Card deleted successfully.');
        $this->redirect('/admin/gift-cards');
    }
}
