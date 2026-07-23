<?php
namespace App\Controllers;
use App\Core\Controller;
use App\Core\Auth;
use App\Core\Session;
use App\Models\User;
use App\Models\WalletTransaction;

class WalletController extends Controller
{
    public function index(): void
    {
        Auth::refreshWallet();
        $transactions = WalletTransaction::getByUser(Auth::id());
        
        $db = \App\Core\Database::getInstance();
        $depositRequests = $db->query(
            "SELECT * FROM deposit_requests WHERE user_id = ? ORDER BY created_at DESC", 
            [Auth::id()]
        )->fetchAll();

        $gateways = getPaymentGateways();
        
        $this->render('wallet/index', [
            'pageTitle' => 'Wallet — ShopX Global',
            'wallet' => Auth::wallet(),
            'transactions' => $transactions,
            'depositRequests' => $depositRequests,
            'gateways' => $gateways
        ]);
    }
    
    public function addFunds(): void
    {
        $this->validateCsrf();
        $amount = (float) $this->input('amount', 0);
        $gatewayId = (int) $this->input('gateway_id', 0);
        $gatewayName = $this->input('gateway_name', 'Unknown Gateway');
        $notes = trim($this->input('notes', ''));
        
        if ($amount <= 0) {
            Session::flash('error', 'Amount must be greater than 0.');
            $this->redirect('/wallet');
            return;
        }
        
        // Handle screenshot upload
        $screenshotPath = '';
        if (isset($_FILES['screenshot']) && $_FILES['screenshot']['error'] === UPLOAD_ERR_OK) {
            $tmpName = $_FILES['screenshot']['tmp_name'];
            $name = basename($_FILES['screenshot']['name']);
            $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
            
            if (in_array($ext, ['png', 'jpg', 'jpeg'])) {
                $uploadDir = APP_PATH . '/../public/uploads/proofs/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                $filename = uniqid('proof_', true) . '.' . $ext;
                if (move_uploaded_file($tmpName, $uploadDir . $filename)) {
                    $screenshotPath = 'uploads/proofs/' . $filename;
                }
            }
        }
        
        if (empty($screenshotPath)) {
            // Mock screenshot upload if none selected (for testing convenience)
            $screenshotPath = 'assets/img/mock_proof.png';
        }
        
        $db = \App\Core\Database::getInstance();
        $db->query(
            "INSERT INTO deposit_requests (user_id, amount, gateway_id, gateway_name, screenshot_path, notes, status) VALUES (?, ?, ?, ?, ?, ?, 'pending')",
            [Auth::id(), $amount, $gatewayId, $gatewayName, $screenshotPath, $notes]
        );
        
        Session::flash('success', 'Deposit request submitted successfully! Funds will reflect once approved by the administrator.');
        $this->redirect('/wallet');
    }
}
