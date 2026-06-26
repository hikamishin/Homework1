<?php
require_once __DIR__ . '/includes/bootstrap.php';

$action   = (string) ($_POST['action'] ?? '');
$id       = (string) ($_POST['id'] ?? '');
$card     = $id !== '' ? find_card($id) : null;
$redirect = (string) ($_POST['redirect'] ?? '');

// 安全過濾跳轉目標
if ($redirect === '' || strpos($redirect, '://') !== false
    || strpos($redirect, "\n") !== false || strpos($redirect, "\r") !== false) {
    $redirect = 'index.php';
}

// 需要登入的動作
if (in_array($action, ['add_collection', 'remove_collection', 'submit_report', 'submit_review'], true)) {
    require_login();
}

// -------------------------------------------------------
// 購物車
// -------------------------------------------------------
if ($action === 'add_cart' && $card !== null) {
    cart_add($id);
    $_SESSION['flash'] = '已加入購物車。';
    redirect_to($redirect);
}

if ($action === 'remove_cart') {
    cart_remove($id);
    $_SESSION['flash'] = '已從購物車移除。';
    redirect_to('cart.php');
}

if ($action === 'checkout') {
    if (empty(cart_items())) {
        $_SESSION['flash'] = '購物車目前沒有商品。';
        redirect_to('cart.php');
    }

    require_login();

    try {
        $orderNumber = create_order();
        $_SESSION['flash'] = '訂單 ' . $orderNumber . ' 已建立！';
    } catch (Throwable $e) {
        $_SESSION['flash'] = '建立訂單失敗，請再試一次。';
    }

    redirect_to('cart.php');
}

// -------------------------------------------------------
// 收藏
// -------------------------------------------------------
if ($action === 'add_collection' && $card !== null) {
    collection_add($id);
    $_SESSION['flash'] = '已加入收藏管理。';
    redirect_to('collection.php');
}

if ($action === 'remove_collection') {
    collection_remove($id);
    $_SESSION['flash'] = '已從收藏移除。';
    redirect_to('collection.php');
}

// -------------------------------------------------------
// 評價
// -------------------------------------------------------
if ($action === 'submit_review' && $card !== null) {
    $rating  = (int) ($_POST['rating'] ?? 0);
    $comment = trim((string) ($_POST['comment'] ?? ''));

    if ($rating < 1 || $rating > 5 || $comment === '') {
        $_SESSION['flash'] = '請填寫完整評價內容。';
        redirect_to($redirect);
    }

    submit_review($card['id'], $card['name'], $rating, $comment);
    $_SESSION['flash'] = '評價已送出，通過管理員審核後會顯示。';
    redirect_to($redirect);
}

// -------------------------------------------------------
// 檢舉
// -------------------------------------------------------
if ($action === 'submit_report' && $card !== null) {
    $reason  = trim((string) ($_POST['reason'] ?? ''));
    $message = trim((string) ($_POST['message'] ?? ''));

    if ($reason === '' || $message === '') {
        $_SESSION['flash'] = '請填寫完整檢舉內容。';
        redirect_to($redirect);
    }

    submit_report($card['id'], $card['name'], $reason, $message);
    $_SESSION['flash'] = '檢舉已送出，管理員會在後台處理。';
    redirect_to($redirect);
}

// -------------------------------------------------------
// 後台：更新檢舉狀態
// -------------------------------------------------------
if ($action === 'update_report') {
    require_admin();
    $reportCode = (string) ($_POST['report_id'] ?? '');
    $status     = (string) ($_POST['status'] ?? '');

    if (in_array($status, ['processing', 'resolved', 'rejected'], true)) {
        update_report_status($reportCode, $status);
    }

    $_SESSION['flash'] = '檢舉狀態已更新。';
    redirect_to('admin/index.php');
}

// -------------------------------------------------------
// 後台：更新評價狀態
// -------------------------------------------------------
if ($action === 'update_review') {
    require_admin();
    $reviewCode = (string) ($_POST['review_id'] ?? '');
    $status     = (string) ($_POST['status'] ?? '');

    if (in_array($status, ['approved', 'rejected'], true)) {
        update_review_status($reviewCode, $status);
    }

    $_SESSION['flash'] = '評價審核狀態已更新。';
    redirect_to('admin/index.php');
}

// -------------------------------------------------------
// 後台：更新卡牌價格/庫存
// -------------------------------------------------------
if ($action === 'update_card') {
    require_admin();
    $price = (int) ($_POST['price'] ?? -1);
    $stock = (int) ($_POST['stock'] ?? -1);

    if ($card === null || $price < 0 || $stock < 0) {
        $_SESSION['flash'] = '價格或庫存資料不正確。';
        redirect_to('admin/index.php');
    }

    update_card_override($id, $price, $stock);
    $_SESSION['flash'] = $card['name'] . ' 的價格與庫存已更新。';
    redirect_to('admin/index.php');
}

$_SESSION['flash'] = '操作失敗，請再試一次。';
redirect_to('index.php');
