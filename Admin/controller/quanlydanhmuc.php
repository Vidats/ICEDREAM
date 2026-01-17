<?php
include_once '../model/CategoryModel.php';

$categoryModel = new CategoryModel();

if (isset($_POST['add_category'])) {
    $name = $_POST['name'];
    $description = $_POST['description'] ?? '';
    $categoryModel->addCategory($name, $description);
    header("Location: ../View/quanlydanhmuc.php");
}

if (isset($_POST['edit_category'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $description = $_POST['description'] ?? '';
    $categoryModel->updateCategory($id, $name, $description);
    header("Location: ../View/quanlydanhmuc.php");
}

if (isset($_GET['delete_category'])) {
    $id = $_GET['delete_category'];
    $categoryModel->deleteCategory($id);
    header("Location: ../View/quanlydanhmuc.php");
}
?>
