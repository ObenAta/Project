<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>FAQ - My Online Store</title>
  <link rel="stylesheet" href="css/style.css">
  <style>
    .faq-container {
      max-width: 900px;
      margin: 40px auto;
      padding: 20px;
      background: #fff;
      border: 1px solid #ddd;
      border-radius: 8px;
      box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .faq-item {
      margin-bottom: 20px;
    }
    .faq-item h3 {
      margin: 0 0 10px;
      color: #333;
    }
    .faq-item p {
      margin: 0 0 10px;
      line-height: 1.6;
      color: #555;
    }
    .faq-footer {
      text-align: center;
      margin-top: 30px;
    }
    .faq-footer a {
      display: inline-block;
      padding: 10px 20px;
      background-color: #007bff;
      color: #fff;
      text-decoration: none;
      border-radius: 4px;
    }
    .faq-footer a:hover {
      background-color: #0056b3;
    }
  </style>
</head>
<body>
  <?php include 'templates/header.php'; ?>
  <main>
    <div class="faq-container">
      <h2 style="text-align: center;">Frequently Asked Questions</h2>
      
      <div class="faq-item">
        <h3>1. What are the key specifications and architecture of the RTX 5080?</h3>
        <p>
          Answer: The GeForce RTX 5080 is built on NVIDIA’s new “Blackwell” GPU architecture (succeeding the Ada Lovelace generation). It features 10,752 CUDA cores, next-gen 5th-generation Tensor Cores for AI and 4th-generation RT Cores for ray tracing. It comes with 16 GB of GDDR7 memory on a 256-bit bus (up to 960 GB/s bandwidth), approximately 2.3 GHz base and 2.6 GHz boost clocks, and a standard dual-slot design with about 360 W peak power using a single 16-pin connector.
        </p>
      </div>
      
      <div class="faq-item">
        <h3>2. How does the RTX 5080 perform in gaming and productivity tasks?</h3>
        <p>
          Answer (Gaming): It delivers excellent performance for 1440p and 4K gaming at max settings with smooth frame rates. It’s about 10–20% faster than the RTX 4080 and benefits greatly from DLSS 4 and Multi-Frame Generation.
        </p>
        <p>
          Answer (Productivity): It excels in AI and compute workloads with 336 Tensor Cores, offers advanced features similar to the RTX 5090 at a lower price, and outperforms previous-gen cards in rendering, encoding, and scientific benchmarks.
        </p>
      </div>
      
      <div class="faq-item">
        <h3>3. What is the price and availability of the RTX 5080?</h3>
        <p>
          Answer: The launch price is $999 USD for the Founders Edition (MSRP). It was released on January 30, 2025, after a CES unveiling. Initial stock was limited, with many Founders Edition cards selling out quickly. Availability is expected to improve a few weeks after launch.
        </p>
      </div>
      
      <div class="faq-item">
        <h3>4. Is the RTX 5080 compatible with my motherboard, and what are the power supply requirements?</h3>
        <p>
          Answer: Yes. It uses a standard PCI Express x16 interface (PCIe 5.0), which is backward-compatible with PCIe 4.0 and 3.0. The card is about 304 mm long in a true 2-slot design. Ensure your case has adequate clearance (~12 inches) and airflow.
        </p>
      </div>
      
      <div class="faq-item">
        <h3>5. What is the recommended power supply for the RTX 5080?</h3>
        <p>
          Answer: NVIDIA recommends at least an 850 W PSU. The card draws up to 360 W and uses a new 16-pin 12VHPWR connector (with an adapter converting 3× 8-pin PCIe plugs to 16-pin). A high-quality PSU is advised.
        </p>
      </div>
      
      <div class="faq-item">
        <h3>6. What kind of cooling solutions and thermal performance does the RTX 5080 have?</h3>
        <p>
          Answer: It uses a dual-fan “flow-through” cooling design that efficiently exhausts heat from both ends of the card. Under full load, temperatures typically remain around 70–75 °C, with quiet operation due to effective heat dissipation.
        </p>
      </div>
      
      <div class="faq-item">
        <h3>7. What new features and improvements does the RTX 5080 offer over previous RTX models?</h3>
        <p>
          Answer: It introduces NVIDIA’s Blackwell architecture with a redesigned SM, more CUDA cores, 5th-generation Tensor Cores, and 4th-generation RT Cores. It supports DLSS 4 and Multi-Frame Generation, offering enhanced performance and efficiency compared to previous generations.
        </p>
      </div>
      
      <div class="faq-item">
        <h3>8. How does the RTX 5080 compare with other GPUs like the RTX 4080, RTX 4090, or AMD’s competitors?</h3>
        <p>
          Answer: The RTX 5080 is the second-fastest card in NVIDIA’s lineup, offering a 10–20% uplift over the RTX 4080 and slightly lower performance than the RTX 4090, but at a much lower price. Against AMD’s Radeon RX 7900 XTX, it excels in ray tracing and AI-based upscaling thanks to DLSS 4 and Multi-Frame Generation.
        </p>
      </div>
      
      <div class="faq-item">
        <h3>9. Are there any additional benefits or considerations for purchasing the RTX 5080?</h3>
        <p>
          Answer: Yes. It offers a strong balance of performance and price, improved energy efficiency, and robust software/driver support. Additional factors to consider include warranty, customer support, and any bundled services or software that add value.
        </p>
      </div>
      
      <div class="faq-footer">
        <a href="contact.php">Still have questions? Ask us!</a>
      </div>
    </div>
  </main>
  <?php include 'templates/footer.php'; ?>
</body>
</html>
