<?php
// frontend/admin/penerima-process.php
session_start();
require_once __DIR__ . '/../../backend/models/OrganizationModel.php';

header('Content-Type: application/json');

$organizationModel = new OrganizationModel();
$response = ['success' => false, 'message' => ''];

$action = $_GET['action'] ?? '';

if ($action === 'add') {
    // Create organization
    $data = [
        'nama_organisasi' => $_POST['nama_organisasi'] ?? '',
        'alamat' => $_POST['alamat'] ?? '',
        'email_kontak' => $_POST['email_kontak'] ?? '',
        'no_kontak' => $_POST['no_kontak'] ?? ''
    ];

    if (empty($data['nama_organisasi'])) {
        $response['message'] = 'Nama organisasi wajib diisi';
    } else {
        $id = $organizationModel->createOrganization($data);
        if ($id) {
            $response['success'] = true;
            $response['message'] = 'Organisasi berhasil ditambahkan';
        } else {
            $response['message'] = 'Gagal menambahkan organisasi';
        }
    }
} elseif ($action === 'edit') {
    // Update organization
    $id = $_POST['id_organisasi'] ?? 0;
    $data = [
        'nama_organisasi' => $_POST['nama_organisasi'] ?? '',
        'alamat' => $_POST['alamat'] ?? '',
        'email_kontak' => $_POST['email_kontak'] ?? '',
        'no_kontak' => $_POST['no_kontak'] ?? ''
    ];

    if (empty($data['nama_organisasi'])) {
        $response['message'] = 'Nama organisasi wajib diisi';
    } elseif (!$organizationModel->getOrganizationById($id)) {
        $response['message'] = 'Organisasi tidak ditemukan';
    } else {
        if ($organizationModel->updateOrganization($id, $data)) {
            $response['success'] = true;
            $response['message'] = 'Organisasi berhasil diperbarui';
        } else {
            $response['message'] = 'Gagal memperbarui organisasi';
        }
    }
} else {
    $response['message'] = 'Aksi tidak valid';
}

echo json_encode($response);
?>