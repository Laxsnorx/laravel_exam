<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Product Manager</title>
</head>
<body>

  <h1>Create Product</h1>
  <form id="product-form">
    <input type="text" name="name" placeholder="Name" required>
    <textarea name="description" placeholder="Description" required></textarea>
    <input type="number" name="model" placeholder="Model" required>
    <input type="file" name="image" accept="image/png, image/jpeg">
    <button type="submit">Submit</button>
  </form>

  <h1>Product List</h1>
  <div id="product-list"></div>

  <script>
    const apiUrl = 'http://127.0.0.1:8000/api/product';
    
    document.getElementById('product-form').addEventListener('submit', async (e) => {
      e.preventDefault();
      const formData = new FormData(e.target);
      try {
        const res = await fetch(apiUrl, { method: 'POST', body: formData });
        const result = await res.json();
        alert(result.message || 'Product created!');
        e.target.reset();
        loadProducts();
      } catch (err) {
        alert('Upload failed: ' + err.message);
      }
    });

    async function loadProducts() {
      const res = await fetch(apiUrl);
      const products = await res.json();
      document.getElementById('product-list').innerHTML = products.map(product =>
        `<div>
          <h3>${product.name}</h3>
          <p>${product.description}</p>
          <p>Model: ${product.model}</p>
          ${product.image ? `<img src="http://127.0.0.1:8000/storage/${product.image}" alt="${product.name}">` : ''}
        </div>`
      ).join('');
    }

    loadProducts();
  </script>

</body>
</html>
