<?php
session_start();
include("DBConn.php");

// User must be logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $clothes_name = trim($_POST["clothes_name"]);
    $category = trim($_POST["category"]);
    $size = trim($_POST["size"]);
    $color = trim($_POST["color"]);
    $price = floatval($_POST["price"]);
    $stock_quantity = intval($_POST["stock_quantity"]);
    $description = trim($_POST["description"]);

    // Validate file upload
    if (isset($_FILES["image"]) && $_FILES["image"]["error"] === UPLOAD_ERR_OK) {

        $uploadDir = __DIR__ . "/uploads/";

        // Create uploads folder if it doesn't exist
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        // Generate unique file name
        $ext = pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION);
        $imageName = uniqid("clothes_", true) . "." . $ext;

        $targetFile = $uploadDir . $imageName;

        // Move uploaded file
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {

            // Insert into database (tblClothes)
            $stmt = $conn->prepare(
                "INSERT INTO tblClothes 
                (user_id, clothes_name, category, size, color, price, stock_quantity, description, image_path) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)"
            );

            $stmt->bind_param(
                "isssssiss",
                $_SESSION["user_id"],
                $clothes_name,
                $category,
                $size,
                $color,
                $price,
                $stock_quantity,
                $description,
                $imageName
            );

            if ($stmt->execute()) {
                $message = "Item uploaded successfully!";
            } else {
                $message = "Database error: Could not save item.";
            }

            $stmt->close();

        } else {
            $message = "Error: Failed to move uploaded file.";
        }

    } else {
        $message = "Error: Please upload an image.";
    }
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html class="dark" lang="en">
<head>
<meta charset="utf-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>

<link href="https://fonts.googleapis.com/css2?family=Epilogue:wght@300;400;700;800&family=Manrope:wght@300;400;500;600&display=swap" rel="stylesheet"/>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>

<title>Upload Clothes</title>

<style>
  body {
    font-family: 'Manrope', sans-serif;
    background-color: #0e0e0e;
    color: #e7e5e4;
  }
</style>
</head>

<body class="bg-[#0e0e0e] text-[#e7e5e4] min-h-screen">

<header class="w-full px-8 py-6 border-b border-gray-700 flex justify-between items-center">
  <h1 class="font-bold tracking-[0.3em] uppercase text-xl">PASTIMAS</h1>
  <a href="dashboard.php" class="text-sm uppercase tracking-widest text-gray-400 hover:text-white">Back</a>
</header>

<main class="max-w-2xl mx-auto px-8 py-12">

  <h2 class="text-3xl font-bold mb-6 tracking-tight">Upload Clothing Item</h2>

  <?php if ($message): ?>
    <div class="mb-6 p-4 border border-gray-600 rounded bg-gray-900">
      <p class="text-sm uppercase tracking-widest"><?php echo htmlspecialchars($message); ?></p>
    </div>
  <?php endif; ?>

  <form method="POST" enctype="multipart/form-data" action="uploads.php" class="space-y-6">

    <div>
      <label class="block text-sm uppercase tracking-widest text-gray-400 mb-2">Clothes Name</label>
      <input type="text" name="clothes_name" required
        class="w-full bg-gray-900 border border-gray-700 rounded px-4 py-3 text-white"/>
    </div>

    <div>
      <label class="block text-sm uppercase tracking-widest text-gray-400 mb-2">Category</label>
      <input type="text" name="category" required
        placeholder="Tops, Bottoms, Dresses..."
        class="w-full bg-gray-900 border border-gray-700 rounded px-4 py-3 text-white"/>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <div>
        <label class="block text-sm uppercase tracking-widest text-gray-400 mb-2">Size</label>
        <input type="text" name="size" required
          placeholder="S / M / L / 32..."
          class="w-full bg-gray-900 border border-gray-700 rounded px-4 py-3 text-white"/>
      </div>

      <div>
        <label class="block text-sm uppercase tracking-widest text-gray-400 mb-2">Color</label>
        <input type="text" name="color" required
          class="w-full bg-gray-900 border border-gray-700 rounded px-4 py-3 text-white"/>
      </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <div>
        <label class="block text-sm uppercase tracking-widest text-gray-400 mb-2">Price (R)</label>
        <input type="number" step="0.01" name="price" required
          class="w-full bg-gray-900 border border-gray-700 rounded px-4 py-3 text-white"/>
      </div>

      <div>
        <label class="block text-sm uppercase tracking-widest text-gray-400 mb-2">Stock Quantity</label>
        <input type="number" name="stock_quantity" min="1" required
          class="w-full bg-gray-900 border border-gray-700 rounded px-4 py-3 text-white"/>
      </div>
    </div>

    <div>
      <label class="block text-sm uppercase tracking-widest text-gray-400 mb-2">Description</label>
      <textarea name="description" required
        class="w-full bg-gray-900 border border-gray-700 rounded px-4 py-3 text-white"
        rows="4"></textarea>
    </div>

    <div>
      <label class="block text-sm uppercase tracking-widest text-gray-400 mb-2">Upload Image</label>
      <input type="file" name="image" accept="image/*" required
        class="w-full bg-gray-900 border border-gray-700 rounded px-4 py-3 text-white"/>
    </div>

    <button type="submit"
      class="w-full bg-gray-200 text-black py-4 rounded uppercase tracking-widest font-bold hover:bg-white transition-all">
      Upload Item
    </button>

  </form>

</main>

</body>
</html>