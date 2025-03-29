<?php
require_once 'includes/config.php';
$searchQuery = "";
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Search Products</title>
  <link rel="stylesheet" href="css/style.css">
  <!-- Material Symbols Outlined for Search Icon -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&icon_names=search" />
  <style>
    .material-symbols-outlined {
      font-variation-settings:
      'FILL' 0,
      'wght' 400,
      'GRAD' 0,
      'opsz' 24;
    }
    /* Container for search */
    .search-container {
      margin: 20px auto;
      text-align: center;
      position: relative;
    }
    /* Search form styling */
    .search-form {
      display: inline-flex;
      align-items: center;
      position: relative;
    }
    /* Search input styling */
    .search-input {
      width: 300px;
      padding: 10px 15px;
      border: 1px solid #ccc;
      border-radius: 30px;
      outline: none;
      font-size: 1em;
      transition: width 0.3s ease;
    }
    .search-input:focus {
      width: 350px;
    }
    /* Search button styling */
    .search-button {
      background-color: #333;
      color: #fff;
      border: none;
      margin-left: -40px;
      border-radius: 50%;
      cursor: pointer;
      width: 35px;
      height: 35px;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    /* Suggestions dropdown styling */
    .suggestions {
      position: absolute;
      top: 100%;
      left: 50%;
      transform: translateX(-50%);
      background: #fff;
      border: 1px solid #ccc;
      border-top: none;
      width: 350px;
      max-height: 200px;
      overflow-y: auto;
      display: none;
      z-index: 100;
      color: #333;
    }
    .suggestion-item {
      padding: 10px;
      cursor: pointer;
    }
    .suggestion-item:hover {
      background-color: #f2f2f2;
    }
    /* Did you mean styling */
    .did-you-mean {
      margin-top: 10px;
      color: #555;
      font-style: italic;
    }
    /* Product list styling */
    .product-list {
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
      gap: 20px;
      padding: 20px;
    }
    .product-item {
      border: 1px solid #ddd;
      padding: 10px;
      width: 200px;
      text-align: center;
      background: #fff;
    }
    .product-item img {
      max-width: 100%;
      height: auto;
      margin-bottom: 10px;
    }
  </style>
</head>
<body>
  <?php include 'templates/header.php'; ?>
  <main>
    <div class="search-container">
      <form action="search.php" method="get" class="search-form" id="search-form">
        <input type="text" name="q" class="search-input" placeholder="Search products..." id="search-input" value="<?php echo htmlspecialchars($searchQuery); ?>">
        <button type="submit" class="search-button">
          <span class="material-symbols-outlined">search</span>
        </button>
      </form>
      <div class="suggestions" id="suggestions"></div>
      <div class="did-you-mean" id="did-you-mean"></div>
    </div>
    <div class="product-list" id="product-list">
      <!-- Dynamic product results will appear here -->
    </div>
  </main>
  <?php include 'templates/footer.php'; ?>

  <script>
    const searchInput = document.getElementById('search-input');
    const suggestionsBox = document.getElementById('suggestions');
    const didYouMeanBox = document.getElementById('did-you-mean');
    const productList = document.getElementById('product-list');

    // Fetch live suggestions for autocomplete
    async function fetchSuggestions(query) {
      if (query.trim() === "") {
        suggestionsBox.style.display = "none";
        return;
      }
      const response = await fetch(`live_search.php?q=${encodeURIComponent(query)}`);
      const suggestions = await response.json();
      if (suggestions.length > 0) {
        suggestionsBox.innerHTML = "";
        suggestions.forEach(suggestion => {
          const div = document.createElement('div');
          div.className = 'suggestion-item';
          div.textContent = suggestion;
          div.addEventListener('click', function(){
            searchInput.value = suggestion;
            suggestionsBox.style.display = "none";
            fetchProducts(suggestion);
          });
          suggestionsBox.appendChild(div);
        });
        suggestionsBox.style.display = "block";
      } else {
        suggestionsBox.style.display = "none";
      }
    }

    // Fetch filtered products without reloading page
    async function fetchProducts(query) {
      // If query is empty, clear the results and suggestions
      if (query.trim() === "") {
        productList.innerHTML = "";
        didYouMeanBox.textContent = "";
        return;
      }
      const response = await fetch(`search_products.php?q=${encodeURIComponent(query)}`);
      const products = await response.json();
      productList.innerHTML = "";
      if (products.length > 0) {
        didYouMeanBox.textContent = "";
        products.forEach(product => {
          const div = document.createElement('div');
          div.className = 'product-item';
          div.innerHTML = `
            <img src="images/${product.image}" alt="${product.name}">
            <h3>${product.name}</h3>
            <p>$${product.price}</p>
            <a href="product_details.php?id=${product.id}">View Details</a>
          `;
          productList.appendChild(div);
        });
      } else {
        productList.innerHTML = "<p>No products found matching your query.</p>";
        // Fetch a "Did you mean" suggestion
        const responseDidYouMean = await fetch(`did_you_mean.php?q=${encodeURIComponent(query)}`);
        const result = await responseDidYouMean.json();
        if (result.suggestion) {
          didYouMeanBox.textContent = `Did you mean: ${result.suggestion}?`;
        }
      }
    }

    // Attach event listeners to search input
    searchInput.addEventListener('input', function(){
      const query = this.value;
      fetchSuggestions(query);
      fetchProducts(query);
    });

    // Prevent default form submission (for AJAX)
    document.getElementById('search-form').addEventListener('submit', function(e){
      e.preventDefault();
      fetchProducts(searchInput.value);
    });

    if (searchInput.value.trim() !== "") {
      fetchProducts(searchInput.value.trim());
    }
  </script>
</body>
</html>
