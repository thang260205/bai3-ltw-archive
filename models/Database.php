<?php
/**
 * Common database helper functions
 */

/**
 * Get all records from a table with optional WHERE clause
 */
function getAllRecords(mysqli $conn, string $table, string $where = '', string $order = '', string $limit = '') {
    $sql = "SELECT * FROM $table";
    if (!empty($where)) {
        $sql .= " WHERE $where";
    }
    if (!empty($order)) {
        $sql .= " ORDER BY $order";
    }
    if (!empty($limit)) {
        $sql .= " LIMIT $limit";
    }
    return mysqli_query($conn, $sql);
}

/**
 * Get single record by ID
 */
function getRecordById(mysqli $conn, string $table, string $id_column, $id) {
    $id = intval($id);
    $sql = "SELECT * FROM $table WHERE $id_column = $id";
    $result = mysqli_query($conn, $sql);
    return $result && mysqli_num_rows($result) > 0 ? mysqli_fetch_assoc($result) : null;
}

/**
 * Count records in a table
 */
function countRecords(mysqli $conn, string $table, string $where = '') {
    $sql = "SELECT COUNT(*) as total FROM $table";
    if (!empty($where)) {
        $sql .= " WHERE $where";
    }
    $result = mysqli_query($conn, $sql);
    return $result ? mysqli_fetch_assoc($result)['total'] : 0;
}

/**
 * Insert a record
 */
function insertRecord(mysqli $conn, string $table, array $data) {
    $columns = implode(',', array_keys($data));
    $values = implode(',', array_map(function($val) use ($conn) {
        if (is_null($val)) return 'NULL';
        if (is_numeric($val)) return $val;
        return "'" . mysqli_real_escape_string($conn, $val) . "'";
    }, array_values($data)));
    
    $sql = "INSERT INTO $table ($columns) VALUES ($values)";
    return mysqli_query($conn, $sql);
}

/**
 * Update a record
 */
function updateRecord(mysqli $conn, string $table, array $data, string $id_column, $id) {
    $id = intval($id);
    $set = [];
    foreach ($data as $key => $val) {
        if (is_null($val)) {
            $set[] = "$key = NULL";
        } elseif (is_numeric($val)) {
            $set[] = "$key = $val";
        } else {
            $set[] = "$key = '" . mysqli_real_escape_string($conn, $val) . "'";
        }
    }
    
    $set_clause = implode(',', $set);
    $sql = "UPDATE $table SET $set_clause WHERE $id_column = $id";
    return mysqli_query($conn, $sql);
}

/**
 * Delete a record
 */
function deleteRecord(mysqli $conn, string $table, string $id_column, $id) {
    $id = intval($id);
    $sql = "DELETE FROM $table WHERE $id_column = $id";
    return mysqli_query($conn, $sql);
}

/**
 * Format currency for display
 */
function formatCurrency($value): string {
    return number_format($value, 0, ',', '.');
}

/**
 * Format date for display
 */
function formatDate($date, string $format = 'd/m/Y H:i'): string {
    if (empty($date)) return '-';
    return date($format, strtotime($date));
}

/**
 * Sanitize string input
 */
function sanitize($value): string {
    global $conn;
    return mysqli_real_escape_string($conn, trim($value));
}

/**
 * Get last error
 */
function getLastError(mysqli $conn): string {
    return mysqli_error($conn);
}

/**
 * Upload image to Cloudinary via REST API
 */
function uploadToCloudinary($file_tmp) {
    // THAY THẾ BẰNG THÔNG TIN CLOUDINARY CỦA BẠN
    $cloud_name = 'dknfvtsse';
    $api_key    = '999621967499792';
    $api_secret = 'gp5ZVx5EGbQOwouzboG1uE9-duw';

    $timestamp = time();
    $signature = sha1("timestamp=" . $timestamp . $api_secret);

    $url = "https://api.cloudinary.com/v1_1/" . $cloud_name . "/image/upload";

    $cfile = new CURLFile($file_tmp);
    $post_params = array(
        'file' => $cfile,
        'api_key' => $api_key,
        'timestamp' => $timestamp,
        'signature' => $signature
    );

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_params);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Bỏ qua check SSL ở localhost
    
    $response = curl_exec($ch);
    curl_close($ch);

    $result = json_decode($response, true);
    if (isset($result['secure_url'])) {
        return $result['secure_url'];
    }
    return false;
}
?>
