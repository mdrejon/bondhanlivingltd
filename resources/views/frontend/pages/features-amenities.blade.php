@extends('frontend.layouts.app')
@section('content')
<style>
  .amenity-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 25px rgba(0,0,0,0.1) !important;
  }
  .banner-overlay-box {
      background: rgba(255, 255, 255, 0.9);
      padding: 50px 40px;
      text-align: center;
      box-shadow: 0 10px 30px rgba(0,0,0,0.1);
      margin-top: 100px;
      margin-bottom: 100px;
      border-radius: 8px;
  }
</style>

    
    <div class="breadcumb-wrapper" data-bg-src="assets/img/bg/breadcumb-bg.jpg">
      <div class="container">
        <div class="breadcumb-content">
          <h1 class="breadcumb-title">FEATURES AND AMENITIES</h1>
          <ul class="breadcumb-menu">
            <li><a href="index.html">Home</a></li>
            <li>Features and Amenities</li>
          </ul>
        </div>
      </div>
    </div>
<!-- Features Section -->
    <section class="space" id="features-sec" style="background-color: #fbfbfb;">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-xl-10">
            <div class="title-area mb-40 text-center">
              <h2 class="sec-title text-uppercase">FEATURES OF <span class="text-theme">BONDHAN LIVING PRODUCTS</span></h2>
            </div>
            <div class="features-box p-5" style="background: #fff; border-radius: 8px; box-shadow: 0 5px 20px rgba(0,0,0,0.03);">
              <ul class="list-unstyled mb-0 columns-2" style="column-count: 2; column-gap: 40px;">
                    <li class="mb-2" style="font-size: 15px; color: #555;"><i class="fa-solid fa-angles-right text-theme me-2"></i> Quality defined by Bondhan Living Clients.</li>
                    <li class="mb-2" style="font-size: 15px; color: #555;"><i class="fa-solid fa-angles-right text-theme me-2"></i> Quality materials use in projects.</li>
                    <li class="mb-2" style="font-size: 15px; color: #555;"><i class="fa-solid fa-angles-right text-theme me-2"></i> Bondhan Living has individual quality policy.</li>
                    <li class="mb-2" style="font-size: 15px; color: #555;"><i class="fa-solid fa-angles-right text-theme me-2"></i> Renowned architects design Bondhan Living projects.</li>
                    <li class="mb-2" style="font-size: 15px; color: #555;"><i class="fa-solid fa-angles-right text-theme me-2"></i> Bondhan Living is committed to handover in time.</li>
                    <li class="mb-2" style="font-size: 15px; color: #555;"><i class="fa-solid fa-angles-right text-theme me-2"></i> Bondhan Living tries to create an eco- friendly environment.</li>
                    <li class="mb-2" style="font-size: 15px; color: #555;"><i class="fa-solid fa-angles-right text-theme me-2"></i> Bondhan Living also planned layout, so as to provide sufficient fresh air and light or ultimate residential living experience.</li>
                    <li class="mb-2" style="font-size: 15px; color: #555;"><i class="fa-solid fa-angles-right text-theme me-2"></i> Bondhan Living ensures the balance of aesthetics and functionality.</li>
                    <li class="mb-2" style="font-size: 15px; color: #555;"><i class="fa-solid fa-angles-right text-theme me-2"></i> Bondhan Living giving PABX connection (if client want).</li>
                    <li class="mb-2" style="font-size: 15px; color: #555;"><i class="fa-solid fa-angles-right text-theme me-2"></i> Bondhan Living providing deep tube well in each project.</li>
                    <li class="mb-2" style="font-size: 15px; color: #555;"><i class="fa-solid fa-angles-right text-theme me-2"></i> Bondhan Living providing house emergency power supply.</li>
                    <li class="mb-2" style="font-size: 15px; color: #555;"><i class="fa-solid fa-angles-right text-theme me-2"></i> Bondhan Living establishes electricity sub station for project.</li>
                    <li class="mb-2" style="font-size: 15px; color: #555;"><i class="fa-solid fa-angles-right text-theme me-2"></i> Bondhan Living emphasis on community facility.</li>
                    <li class="mb-2" style="font-size: 15px; color: #555;"><i class="fa-solid fa-angles-right text-theme me-2"></i> Bondhan Living giving additional values to their clients.</li>
                    <li class="mb-2" style="font-size: 15px; color: #555;"><i class="fa-solid fa-angles-right text-theme me-2"></i> Security facilities are always available.</li>
                    <li class="mb-2" style="font-size: 15px; color: #555;"><i class="fa-solid fa-angles-right text-theme me-2"></i> Rest room and toilet facilities for the drivers.(Depend on Project)</li>
                    <li class="mb-2" style="font-size: 15px; color: #555;"><i class="fa-solid fa-angles-right text-theme me-2"></i> Bondhan Living LAN if clients wants this.</li>

              </ul>
              <style>
                @media (max-width: 768px) {
                  .columns-2 { column-count: 1 !important; }
                }
              </style>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Amenities Section -->
    <section class="space-bottom" id="amenities-sec" style="background-color: #fbfbfb;">
      <div class="container">
        <div class="title-area mb-50 text-center">
          <h2 class="sec-title text-uppercase">AMENITIES</h2>
        </div>
        <div class="row">

            <div class="col-lg-6 col-md-12 mb-4">
              <div class="amenity-card p-4 h-100" style="background: #fff; border: 1px solid #eee; border-radius: 8px; box-shadow: 0 5px 20px rgba(0,0,0,0.03); transition: all 0.3s ease;">
                <h4 class="mb-4 pb-2" style="border-bottom: 2px solid var(--theme-color); display: inline-block; color: var(--theme-color); font-weight: 700; font-size: 18px; text-transform: uppercase;">DOOR</h4>
                <ul class="list-unstyled mb-0">
                      <li class="mb-2" style="font-size: 14px; color: #666; display: flex;"><i class="fa-solid fa-check text-theme me-2 mt-1"></i> <span>Door Chain</span></li>
                      <li class="mb-2" style="font-size: 14px; color: #666; display: flex;"><i class="fa-solid fa-check text-theme me-2 mt-1"></i> <span>Check Viewer.</span></li>
                      <li class="mb-2" style="font-size: 14px; color: #666; display: flex;"><i class="fa-solid fa-check text-theme me-2 mt-1"></i> <span>Calling Bell Switch of Good Quality.</span></li>
                      <li class="mb-2" style="font-size: 14px; color: #666; display: flex;"><i class="fa-solid fa-check text-theme me-2 mt-1"></i> <span>Solid Brass Door Knocker</span></li>
                      <li class="mb-2" style="font-size: 14px; color: #666; display: flex;"><i class="fa-solid fa-check text-theme me-2 mt-1"></i> <span>Apartment Number in Brass.</span></li>
                      <li class="mb-2" style="font-size: 14px; color: #666; display: flex;"><i class="fa-solid fa-check text-theme me-2 mt-1"></i> <span>Heavy duty door lock of foreign origin.</span></li>
                      <li class="mb-2" style="font-size: 14px; color: #666; display: flex;"><i class="fa-solid fa-check text-theme me-2 mt-1"></i> <span>Internal Doors of Strong and Durable veneer Flush door Shutters with French polish.</span></li>
                      <li class="mb-2" style="font-size: 14px; color: #666; display: flex;"><i class="fa-solid fa-check text-theme me-2 mt-1"></i> <span>All internal door frames are made of sill Koral/ Jarul/ Gutia.</span></li>
                      <li class="mb-2" style="font-size: 14px; color: #666; display: flex;"><i class="fa-solid fa-check text-theme me-2 mt-1"></i> <span>All bathroom doors with inner side water proof laminated plastic door.</span></li>
                      <li class="mb-2" style="font-size: 14px; color: #666; display: flex;"><i class="fa-solid fa-check text-theme me-2 mt-1"></i> <span>All internal doors with good quality round lock</span></li>
                      <li class="mb-2" style="font-size: 14px; color: #666; display: flex;"><i class="fa-solid fa-check text-theme me-2 mt-1"></i> <span>To maintain the highest standards in developing commercial properties.</span></li>
                      <li class="mb-2" style="font-size: 14px; color: #666; display: flex;"><i class="fa-solid fa-check text-theme me-2 mt-1"></i> <span>To maintain the highest standards in developing homes for individuals.</span></li>
                      <li class="mb-2" style="font-size: 14px; color: #666; display: flex;"><i class="fa-solid fa-check text-theme me-2 mt-1"></i> <span>To create safe homes.</span></li>
                      <li class="mb-2" style="font-size: 14px; color: #666; display: flex;"><i class="fa-solid fa-check text-theme me-2 mt-1"></i> <span>To provide feeling of living in a home with ultimate comfort.</span></li>
                      <li class="mb-2" style="font-size: 14px; color: #666; display: flex;"><i class="fa-solid fa-check text-theme me-2 mt-1"></i> <span>To provide good customer service.</span></li>
                      <li class="mb-2" style="font-size: 14px; color: #666; display: flex;"><i class="fa-solid fa-check text-theme me-2 mt-1"></i> <span>To provide professional and personalized services of the highest integrity.</span></li>
                      <li class="mb-2" style="font-size: 14px; color: #666; display: flex;"><i class="fa-solid fa-check text-theme me-2 mt-1"></i> <span>To become an international referral for buyers and investors.</span></li>
                      <li class="mb-2" style="font-size: 14px; color: #666; display: flex;"><i class="fa-solid fa-check text-theme me-2 mt-1"></i> <span>To provide high quality Customer Relationship Management.</span></li>
                      <li class="mb-2" style="font-size: 14px; color: #666; display: flex;"><i class="fa-solid fa-check text-theme me-2 mt-1"></i> <span>To develop a 'brand name'.</span></li>

                </ul>
              </div>
            </div>
    
            <div class="col-lg-6 col-md-12 mb-4">
              <div class="amenity-card p-4 h-100" style="background: #fff; border: 1px solid #eee; border-radius: 8px; box-shadow: 0 5px 20px rgba(0,0,0,0.03); transition: all 0.3s ease;">
                <h4 class="mb-4 pb-2" style="border-bottom: 2px solid var(--theme-color); display: inline-block; color: var(--theme-color); font-weight: 700; font-size: 18px; text-transform: uppercase;">WINDOWS</h4>
                <ul class="list-unstyled mb-0">
                      <li class="mb-2" style="font-size: 14px; color: #666; display: flex;"><i class="fa-solid fa-check text-theme me-2 mt-1"></i> <span>Sliding aluminum windows as per architectural design of the building.</span></li>
                      <li class="mb-2" style="font-size: 14px; color: #666; display: flex;"><i class="fa-solid fa-check text-theme me-2 mt-1"></i> <span>5 mm thick glass with mohair fining.</span></li>
                      <li class="mb-2" style="font-size: 14px; color: #666; display: flex;"><i class="fa-solid fa-check text-theme me-2 mt-1"></i> <span>Safety grills in all outer windows.</span></li>
                      <li class="mb-2" style="font-size: 14px; color: #666; display: flex;"><i class="fa-solid fa-check text-theme me-2 mt-1"></i> <span>Rain water barrier in 4" aluminum section (As per architectural design of the building).</span></li>
                      <li class="mb-2" style="font-size: 14px; color: #666; display: flex;"><i class="fa-solid fa-check text-theme me-2 mt-1"></i> <span>Mosquito Net provision in All Windows</span></li>

                </ul>
              </div>
            </div>
    
            <div class="col-lg-6 col-md-12 mb-4">
              <div class="amenity-card p-4 h-100" style="background: #fff; border: 1px solid #eee; border-radius: 8px; box-shadow: 0 5px 20px rgba(0,0,0,0.03); transition: all 0.3s ease;">
                <h4 class="mb-4 pb-2" style="border-bottom: 2px solid var(--theme-color); display: inline-block; color: var(--theme-color); font-weight: 700; font-size: 18px; text-transform: uppercase;">WALLS</h4>
                <ul class="list-unstyled mb-0">
                      <li class="mb-2" style="font-size: 14px; color: #666; display: flex;"><i class="fa-solid fa-check text-theme me-2 mt-1"></i> <span>Good Quality bricks.</span></li>
                      <li class="mb-2" style="font-size: 14px; color: #666; display: flex;"><i class="fa-solid fa-check text-theme me-2 mt-1"></i> <span>Smooth finished walls.</span></li>
                      <li class="mb-2" style="font-size: 14px; color: #666; display: flex;"><i class="fa-solid fa-check text-theme me-2 mt-1"></i> <span>Exterior wall thickness will be 10" and internal wall thickness will be 5"</span></li>

                </ul>
              </div>
            </div>
    
            <div class="col-lg-6 col-md-12 mb-4">
              <div class="amenity-card p-4 h-100" style="background: #fff; border: 1px solid #eee; border-radius: 8px; box-shadow: 0 5px 20px rgba(0,0,0,0.03); transition: all 0.3s ease;">
                <h4 class="mb-4 pb-2" style="border-bottom: 2px solid var(--theme-color); display: inline-block; color: var(--theme-color); font-weight: 700; font-size: 18px; text-transform: uppercase;">FLOOR FINISH</h4>
                <ul class="list-unstyled mb-0">
                      <li class="mb-2" style="font-size: 14px; color: #666; display: flex;"><i class="fa-solid fa-check text-theme me-2 mt-1"></i> <span>Floors in Homogenous Tiles (RAK /Great walls /equivalent)</span></li>
                      <li class="mb-2" style="font-size: 14px; color: #666; display: flex;"><i class="fa-solid fa-check text-theme me-2 mt-1"></i> <span>Safety grills in verandahs (Except front Verandahs)</span></li>
                      <li class="mb-2" style="font-size: 14px; color: #666; display: flex;"><i class="fa-solid fa-check text-theme me-2 mt-1"></i> <span>All verandahs in floor tiles (RAK / Great walls or Equivalent).</span></li>
                      <li class="mb-2" style="font-size: 14px; color: #666; display: flex;"><i class="fa-solid fa-check text-theme me-2 mt-1"></i> <span>Suitable light points provision.</span></li>

                </ul>
              </div>
            </div>
    
            <div class="col-lg-6 col-md-12 mb-4">
              <div class="amenity-card p-4 h-100" style="background: #fff; border: 1px solid #eee; border-radius: 8px; box-shadow: 0 5px 20px rgba(0,0,0,0.03); transition: all 0.3s ease;">
                <h4 class="mb-4 pb-2" style="border-bottom: 2px solid var(--theme-color); display: inline-block; color: var(--theme-color); font-weight: 700; font-size: 18px; text-transform: uppercase;">PAINTING & POLISHING</h4>
                <ul class="list-unstyled mb-0">
                      <li class="mb-2" style="font-size: 14px; color: #666; display: flex;"><i class="fa-solid fa-check text-theme me-2 mt-1"></i> <span>Plastic Paint in all internal walls and ceilings in soft colors (Berger, Elite or equivalent) Enamel paint on grills and for bathroom ceilings.</span></li>
                      <li class="mb-2" style="font-size: 14px; color: #666; display: flex;"><i class="fa-solid fa-check text-theme me-2 mt-1"></i> <span>Exterior wall will be weather coat paint (Berger, Elite or equivalent).</span></li>
                      <li class="mb-2" style="font-size: 14px; color: #666; display: flex;"><i class="fa-solid fa-check text-theme me-2 mt-1"></i> <span>French polished doorframes & shutters.</span></li>
                      <li class="mb-2" style="font-size: 14px; color: #666; display: flex;"><i class="fa-solid fa-check text-theme me-2 mt-1"></i> <span>Synthetic enamel paint applied on M.S. railing and grilles. (Berger, Elite or equivalent)</span></li>

                </ul>
              </div>
            </div>
    
            <div class="col-lg-6 col-md-12 mb-4">
              <div class="amenity-card p-4 h-100" style="background: #fff; border: 1px solid #eee; border-radius: 8px; box-shadow: 0 5px 20px rgba(0,0,0,0.03); transition: all 0.3s ease;">
                <h4 class="mb-4 pb-2" style="border-bottom: 2px solid var(--theme-color); display: inline-block; color: var(--theme-color); font-weight: 700; font-size: 18px; text-transform: uppercase;">ELECTRICAL</h4>
                <ul class="list-unstyled mb-0">
                      <li class="mb-2" style="font-size: 14px; color: #666; display: flex;"><i class="fa-solid fa-check text-theme me-2 mt-1"></i> <span>Imported goods standard electrical switches, plug points and other fittings.</span></li>
                      <li class="mb-2" style="font-size: 14px; color: #666; display: flex;"><i class="fa-solid fa-check text-theme me-2 mt-1"></i> <span>A/c Power Outlets with earthling connection.</span></li>
                      <li class="mb-2" style="font-size: 14px; color: #666; display: flex;"><i class="fa-solid fa-check text-theme me-2 mt-1"></i> <span>Provision of Air Condition in Master beds, Child Bed & Living room.</span></li>
                      <li class="mb-2" style="font-size: 14px; color: #666; display: flex;"><i class="fa-solid fa-check text-theme me-2 mt-1"></i> <span>Fancy light fixtures in all rooms.</span></li>
                      <li class="mb-2" style="font-size: 14px; color: #666; display: flex;"><i class="fa-solid fa-check text-theme me-2 mt-1"></i> <span>Electrical Distribution Box with Main Switch.</span></li>
                      <li class="mb-2" style="font-size: 14px; color: #666; display: flex;"><i class="fa-solid fa-check text-theme me-2 mt-1"></i> <span>Standard quality concealed electrical wiring</span></li>
                      <li class="mb-2" style="font-size: 14px; color: #666; display: flex;"><i class="fa-solid fa-check text-theme me-2 mt-1"></i> <span>Security Eights in the compound, car parking space and common spaces.</span></li>

                </ul>
              </div>
            </div>
    
            <div class="col-lg-6 col-md-12 mb-4">
              <div class="amenity-card p-4 h-100" style="background: #fff; border: 1px solid #eee; border-radius: 8px; box-shadow: 0 5px 20px rgba(0,0,0,0.03); transition: all 0.3s ease;">
                <h4 class="mb-4 pb-2" style="border-bottom: 2px solid var(--theme-color); display: inline-block; color: var(--theme-color); font-weight: 700; font-size: 18px; text-transform: uppercase;">BATH ROOM FEATURES</h4>
                <ul class="list-unstyled mb-0">
                      <li class="mb-2" style="font-size: 14px; color: #666; display: flex;"><i class="fa-solid fa-check text-theme me-2 mt-1"></i> <span>Uniform Floor slope towards Water Outlet.</span></li>
                      <li class="mb-2" style="font-size: 14px; color: #666; display: flex;"><i class="fa-solid fa-check text-theme me-2 mt-1"></i> <span>Good Quality Sanitary Wares in all bathrooms except servant Toilet</span></li>
                      <li class="mb-2" style="font-size: 14px; color: #666; display: flex;"><i class="fa-solid fa-check text-theme me-2 mt-1"></i> <span>Marble Granite top finished Cabinet Basin in Master Bath.</span></li>
                      <li class="mb-2" style="font-size: 14px; color: #666; display: flex;"><i class="fa-solid fa-check text-theme me-2 mt-1"></i> <span>Other bathroom will have standard pedestal basin.</span></li>
                      <li class="mb-2" style="font-size: 14px; color: #666; display: flex;"><i class="fa-solid fa-check text-theme me-2 mt-1"></i> <span>Good Quality Glazed Tiles in all Bathrooms except Maid bath.</span></li>
                      <li class="mb-2" style="font-size: 14px; color: #666; display: flex;"><i class="fa-solid fa-check text-theme me-2 mt-1"></i> <span>Matching wall tiles in alt bathrooms up to false ceiling (except servant's toilet)</span></li>
                      <li class="mb-2" style="font-size: 14px; color: #666; display: flex;"><i class="fa-solid fa-check text-theme me-2 mt-1"></i> <span>All Mirrors with overhead lamps (Except servant's toilet)</span></li>
                      <li class="mb-2" style="font-size: 14px; color: #666; display: flex;"><i class="fa-solid fa-check text-theme me-2 mt-1"></i> <span>Good Quality Chrome plated fittings in master bathroom including towel, rail, soap case, paper holder etc. (Except servant's toilet)</span></li>
                      <li class="mb-2" style="font-size: 14px; color: #666; display: flex;"><i class="fa-solid fa-check text-theme me-2 mt-1"></i> <span>Tiles on floor and wall up to 5 feet in maid's bathroom with long pan, shower lowdown Exhaustion</span></li>
                      <li class="mb-2" style="font-size: 14px; color: #666; display: flex;"><i class="fa-solid fa-check text-theme me-2 mt-1"></i> <span>Concealed Hot and Cold Water lines provision in master bathroom & Child -1</span></li>

                </ul>
              </div>
            </div>
    
            <div class="col-lg-6 col-md-12 mb-4">
              <div class="amenity-card p-4 h-100" style="background: #fff; border: 1px solid #eee; border-radius: 8px; box-shadow: 0 5px 20px rgba(0,0,0,0.03); transition: all 0.3s ease;">
                <h4 class="mb-4 pb-2" style="border-bottom: 2px solid var(--theme-color); display: inline-block; color: var(--theme-color); font-weight: 700; font-size: 18px; text-transform: uppercase;">KITCHEN FEATURES</h4>
                <ul class="list-unstyled mb-0">
                      <li class="mb-2" style="font-size: 14px; color: #666; display: flex;"><i class="fa-solid fa-check text-theme me-2 mt-1"></i> <span>Impressively designed platform with Granite Marble Worktop.</span></li>
                      <li class="mb-2" style="font-size: 14px; color: #666; display: flex;"><i class="fa-solid fa-check text-theme me-2 mt-1"></i> <span>Double Burner Gas Outlet.</span></li>
                      <li class="mb-2" style="font-size: 14px; color: #666; display: flex;"><i class="fa-solid fa-check text-theme me-2 mt-1"></i> <span>Good Quality glazed/ ceramic wall Tiles up to cabinet height (RAK/equivalent).</span></li>
                      <li class="mb-2" style="font-size: 14px; color: #666; display: flex;"><i class="fa-solid fa-check text-theme me-2 mt-1"></i> <span>Matching Floor Tiles (RAK Homogenous Type).</span></li>
                      <li class="mb-2" style="font-size: 14px; color: #666; display: flex;"><i class="fa-solid fa-check text-theme me-2 mt-1"></i> <span>Space provision for Gas Oven.</span></li>
                      <li class="mb-2" style="font-size: 14px; color: #666; display: flex;"><i class="fa-solid fa-check text-theme me-2 mt-1"></i> <span>Concealed Hot and Cold Water Lines.</span></li>
                      <li class="mb-2" style="font-size: 14px; color: #666; display: flex;"><i class="fa-solid fa-check text-theme me-2 mt-1"></i> <span>One tiled washing area in kitchen verandah.</span></li>
                      <li class="mb-2" style="font-size: 14px; color: #666; display: flex;"><i class="fa-solid fa-check text-theme me-2 mt-1"></i> <span>One Stainless Counter-top Steel Sink with Mixer Cabinet Aluminum.</span></li>
                      <li class="mb-2" style="font-size: 14px; color: #666; display: flex;"><i class="fa-solid fa-check text-theme me-2 mt-1"></i> <span>Suitably Located Exhaust Fan.</span></li>

                </ul>
              </div>
            </div>
    
            <div class="col-lg-6 col-md-12 mb-4">
              <div class="amenity-card p-4 h-100" style="background: #fff; border: 1px solid #eee; border-radius: 8px; box-shadow: 0 5px 20px rgba(0,0,0,0.03); transition: all 0.3s ease;">
                <h4 class="mb-4 pb-2" style="border-bottom: 2px solid var(--theme-color); display: inline-block; color: var(--theme-color); font-weight: 700; font-size: 18px; text-transform: uppercase;">UTILITY LINES</h4>
                <ul class="list-unstyled mb-0">
                      <li class="mb-2" style="font-size: 14px; color: #666; display: flex;"><i class="fa-solid fa-check text-theme me-2 mt-1"></i> <span>Gas & Electricity supply will be individual apartment- wise Meter and connection and water supply will have common meter connection for the project.</span></li>
                      <li class="mb-2" style="font-size: 14px; color: #666; display: flex;"><i class="fa-solid fa-check text-theme me-2 mt-1"></i> <span>All apartments will have independent Gas Connection for two burners.</span></li>
                      <li class="mb-2" style="font-size: 14px; color: #666; display: flex;"><i class="fa-solid fa-check text-theme me-2 mt-1"></i> <span>All apartments will have independent Electricity Meter.</span></li>
                      <li class="mb-2" style="font-size: 14px; color: #666; display: flex;"><i class="fa-solid fa-check text-theme me-2 mt-1"></i> <span>A common WASA meter for total Complex. Connection cost facility landowners apartments will be borne by the developer.</span></li>

                </ul>
              </div>
            </div>
    
            <div class="col-lg-12 col-md-12 mb-4">
              <div class="amenity-card p-4 h-100" style="background: #fff; border: 1px solid #eee; border-radius: 8px; box-shadow: 0 5px 20px rgba(0,0,0,0.03); transition: all 0.3s ease;">
                <h4 class="mb-4 pb-2" style="border-bottom: 2px solid var(--theme-color); display: inline-block; color: var(--theme-color); font-weight: 700; font-size: 18px; text-transform: uppercase;">GENERAL AMENITIES OF THE COMPLEX</h4>
                <ul class="list-unstyled mb-0" style="column-count: 2; column-gap: 40px;">
                  <li class="mb-3" style="font-size: 14px; color: #666; display: flex; align-items: flex-start;"><i class="fa-solid fa-check text-theme me-2 mt-1"></i> <span>Heavy secured gate with decorative lamps and logo.</span></li>
                  <li class="mb-3" style="font-size: 14px; color: #666; display: flex; align-items: flex-start;"><i class="fa-solid fa-check text-theme me-2 mt-1"></i> <span>Reserved car parking in Covered if Protected Basement and Ground floor for residents with comfortable driveway.</span></li>
                  <li class="mb-3" style="font-size: 14px; color: #666; display: flex; align-items: flex-start;"><i class="fa-solid fa-check text-theme me-2 mt-1"></i> <span>Driver's waiting room & toilet.</span></li>
                  <li class="mb-3" style="font-size: 14px; color: #666; display: flex; align-items: flex-start;"><i class="fa-solid fa-check text-theme me-2 mt-1"></i> <span>Impressive main lobby, reception area and staircase in secured premises.</span></li>
                  <li class="mb-3" style="font-size: 14px; color: #666; display: flex; align-items: flex-start;"><i class="fa-solid fa-check text-theme me-2 mt-1"></i> 
                    <div>
                      <span>Lift from reputed international manufacturer to be:</span>
                      <ul class="ms-3 mt-1 list-unstyled" style="font-size: 13px; line-height: 1.6;">
                        <li>a) With enough capacity to serve resident at every floor,</li>
                        <li>b) With adequate lighting,</li>
                        <li>c) With well-finished and attractive door and cabin.</li>
                      </ul>
                    </div>
                  </li>
                  <li class="mb-3" style="font-size: 14px; color: #666; display: flex; align-items: flex-start;"><i class="fa-solid fa-check text-theme me-2 mt-1"></i> <span>Tiles lift lobbies and main staircase with easy to climb steps, adequate lighting and fire protection features.</span></li>
                  <li class="mb-3" style="font-size: 14px; color: #666; display: flex; align-items: flex-start;"><i class="fa-solid fa-check text-theme me-2 mt-1"></i> 
                    <div>
                      <span>Stand-by emergency generator for the following:</span>
                      <ul class="ms-3 mt-1 list-unstyled" style="font-size: 13px; line-height: 1.6;">
                        <li>a. The lift,</li>
                        <li>b. Water pump,</li>
                        <li>c. Lighting in common space and stair,</li>
                        <li>d. Emergency points in each apartment.</li>
                      </ul>
                    </div>
                  </li>
                  <li class="mb-3" style="font-size: 14px; color: #666; display: flex; align-items: flex-start;"><i class="fa-solid fa-check text-theme me-2 mt-1"></i> <span>Electricity Supply approx 220V/440V from PDB source with separate main cable and LT Panel/Distribution Board.</span></li>
                  <li class="mb-3" style="font-size: 14px; color: #666; display: flex; align-items: flex-start;"><i class="fa-solid fa-check text-theme me-2 mt-1"></i> <span>Water Supply Connection from WASA sufficient as per Total calculated Consumption.</span></li>
                  <li class="mb-3" style="font-size: 14px; color: #666; display: flex; align-items: flex-start;"><i class="fa-solid fa-check text-theme me-2 mt-1"></i> <span>Underground Water Reservoir with one Main Lifting Pump and Standby pump.</span></li>
                  <li class="mb-3" style="font-size: 14px; color: #666; display: flex; align-items: flex-start;"><i class="fa-solid fa-check text-theme me-2 mt-1"></i> <span>Sewerage System planned for long-term requirement.</span></li>
                  <li class="mb-3" style="font-size: 14px; color: #666; display: flex; align-items: flex-start;"><i class="fa-solid fa-check text-theme me-2 mt-1"></i> <span>Gas Pipeline Connection from BAKHRABAD Distribution System as per total Calculated Consumption, Adequate Safety Measures incorporated.</span></li>
                  <li class="mb-3" style="font-size: 14px; color: #666; display: flex; align-items: flex-start;"><i class="fa-solid fa-check text-theme me-2 mt-1"></i> <span>Termite Protection Treatment of Ground.</span></li>
                  <li class="mb-3" style="font-size: 14px; color: #666; display: flex; align-items: flex-start;"><i class="fa-solid fa-check text-theme me-2 mt-1"></i> <span>A fire extinguisher on each floor.</span></li>
                  <li class="mb-3" style="font-size: 14px; color: #666; display: flex; align-items: flex-start;"><i class="fa-solid fa-check text-theme me-2 mt-1"></i> <span>Preparation of By-laws and committee formation of Apartment Owners Association. 6 (six) months free service for supervision, repair and rectification of defects.</span></li>
                </ul>
                <style>
                  @media (max-width: 991px) {
                    .amenity-card .list-unstyled { column-count: 1 !important; }
                  }
                </style>
              </div>
            </div>
    
        </div>
      </div>
    </section>

    <!-- Start Partner Area -->
    <div class="partner-area ptb-100">
      <div class="container">
        <div class="partner-slider owl-theme owl-carousel">
          <div class="partner-item">
            <a href="#">
              <img src="assets/img/client/cilent_1_1.png" alt="Image" />
            </a>
          </div>

          <div class="partner-item">
            <a href="#">
              <img src="assets/img/client/cilent_1_2.png" alt="Image" />
            </a>
          </div>

          <div class="partner-item">
            <a href="#">
              <img src="assets/img/client/cilent_1_3.png" alt="Image" />
            </a>
          </div>

          <div class="partner-item">
            <a href="#">
              <img src="assets/img/client/cilent_1_4.png" alt="Image" />
            </a>
          </div>

          <div class="partner-item">
            <a href="#">
              <img src="assets/img/client/cilent_1_5.png" alt="Image" />
            </a>
          </div>
        </div>
      </div>
    </div>
    <!-- End Partner Area -->
@endsection
