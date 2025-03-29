<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Regional In-Store Pick Up Availability Map</title>
  <link rel="stylesheet" href="css/style.css">
  <style>
    body {
      font-family: 'Roboto Condensed', sans-serif;
      background-color: #f4f4f4;
      margin: 0;
      padding: 20px;
    }
    .map-container {
      max-width: 900px;
      margin: 40px auto;
      padding: 20px;
      background: #fff;
      border: 1px solid #ddd;
      border-radius: 8px;
      box-shadow: 0 2px 4px rgba(0,0,0,0.1);
      text-align: center;
    }
    .map-container h2 {
      margin-bottom: 20px;
    }
    .map-container img {
      max-width: 100%;
      height: auto;
      border: 1px solid #ccc;
      border-radius: 4px;
      display: block;
      margin: 0 auto;
    }
    #availability-display {
      margin-top: 20px;
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 4px;
      background-color: #e9ecef;
      font-size: 1.1rem;
      min-height: 50px;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    .available {
      color: green;
      font-weight: bold;
    }
    .unavailable {
      color: red;
      font-weight: bold;
    }
  </style>
  <script>
    document.addEventListener("DOMContentLoaded", function(){
      // Define availability messages for each region
      const availability = {
        "Ontario": "Ontario: In-store pick up available in Ottawa.",
        "Quebec": "Quebec: In-store pick up available in Quebec City.",
        "Manitoba": "Manitoba: In-store pick up available in Winnipeg.",
        "Nunavut": "Nunavut: In-store pick up not available.",
        "Alberta": "Alberta: In-store pick up available in Edmonton.",
        "Northwestern Territories": "Northwestern Territories: In-store pick up not available.",
        "Yukon": "Yukon: In-store pick up not available.",
        "British Columbia": "British Columbia: In-store pick up available in Victoria.",
        "New Brunswick": "New Brunswick: In-store pick up available in Fredericton.",
        "Nova Scotia": "Nova Scotia: In-store pick up not available in Halifax.",
        "Newfoundland and Labrador": "Newfoundland and Labrador: In-store pick up not available in St. John's.",
        "Prince Edward Islands": "Prince Edward Islands: In-store pick up not available in Charlottetown.",
        "Saskatchewan": "Saskatchewan: In-store pick up available in Regina."
      };

      window.showAvailability = function(region) {
        const displayDiv = document.getElementById("availability-display");
        const message = availability[region] || "Information not available for " + region;
        if (message.toLowerCase().includes("not available")) {
          displayDiv.innerHTML = `<span class="unavailable">${message}</span>`;
        } else {
          displayDiv.innerHTML = `<span class="available">${message}</span>`;
        }
      };
    });
  </script>
  <!-- Include Image Map Resizer library from CDN -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/image-map-resizer/1.0.10/js/imageMapResizer.min.js" defer></script>
  <script>
    window.addEventListener("load", function(){
      if(document.querySelector('map')) {
        imageMapResize();
      }
    });
  </script>
</head>
<body>
  <?php include 'templates/header.php'; ?>
  <main>
    <div class="map-container">
      <h2>Regional In-Store Pick Up Availability Map (Canada)</h2>
      <!-- Responsive image map without fixed dimensions -->
      <img src="images/canada_map.png" alt="Canada Map" usemap="#canada-map">
      <map name="canada-map">
        <!-- Ontario -->
        <area shape="rect" coords="451,407,515,423" alt="Ontario" href="javascript:void(0)" onclick="showAvailability('Ontario')">
        <!-- Quebec -->
        <area shape="rect" coords="560,368,624,384" alt="Quebec" href="javascript:void(0)" onclick="showAvailability('Quebec')">
        <!-- Manitoba -->
        <area shape="rect" coords="373,352,437,368" alt="Manitoba" href="javascript:void(0)" onclick="showAvailability('Manitoba')">
        <!-- Nunavut -->
        <area shape="rect" coords="373,228,437,244" alt="Nunavut" href="javascript:void(0)" onclick="showAvailability('Nunavut')">
        <!-- Alberta -->
        <area shape="rect" coords="240,341,304,357" alt="Alberta" href="javascript:void(0)" onclick="showAvailability('Alberta')">
        <!-- Northwestern Territories -->
        <area shape="rect" coords="247,236,331,263" alt="Northwestern Territories" href="javascript:void(0)" onclick="showAvailability('Northwestern Territories')">
        <!-- Yukon -->
        <area shape="rect" coords="153,187,237,214" alt="Yukon" href="javascript:void(0)" onclick="showAvailability('Yukon')">
        <!-- British Columbia -->
        <area shape="rect" coords="166,300,250,327" alt="British Columbia" href="javascript:void(0)" onclick="showAvailability('British Columbia')">
        <!-- New Brunswick -->
        <area shape="rect" coords="628,455,712,482" alt="New Brunswick" href="javascript:void(0)" onclick="showAvailability('New Brunswick')">
        <!-- Nova Scotia -->
        <area shape="rect" coords="705,431,763,457" alt="Nova Scotia" href="javascript:void(0)" onclick="showAvailability('Nova Scotia')">
        <!-- Newfoundland and Labrador -->
        <area shape="rect" coords="654,261,760,293" alt="Newfoundland and Labrador" href="javascript:void(0)" onclick="showAvailability('Newfoundland and Labrador')">
        <!-- Prince Edward Islands -->
        <area shape="rect" coords="679,391,713,408" alt="Prince Edward Islands" href="javascript:void(0)" onclick="showAvailability('Prince Edward Islands')">
        <!-- Saskatchewan -->
        <area shape="rect" coords="286,386,383,402" alt="Saskatchewan" href="javascript:void(0)" onclick="showAvailability('Saskatchewan')">
      </map>
      <div id="availability-display">
        Click on a region to see availability.
      </div>
      <p>
         Need help using this map? 
         <a href="maphelp.html">Click here for instructions.</a>
      </p>
    </div>
  </main>
  <?php include 'templates/footer.php'; ?>
</body>
</html>

