@extends('frontend.layouts.app')
@section('content')
<div class="breadcumb-wrapper" data-bg-src="assets/img/bg/breadcumb-bg.jpg">
      <div class="container">
        <div class="breadcumb-content">
          <h1 class="breadcumb-title">Loan Calculator</h1>
          <ul class="breadcumb-menu">
            <li><a href="index.html">Home</a></li>
            <li>Loan Calculator</li>
          </ul>
        </div>
      </div>
    </div>

    
    
    <div class="space" id="calculator-sec" style="background-color: #f8f9fa;">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-xl-6 col-lg-8 col-md-10">
            <div class="calculator-card p-5" style="background: #fff; border: 1px solid #eaeaea; box-shadow: 0 10px 30px rgba(0,0,0,0.05); border-radius: 4px;">
              
              <div class="title-area mb-4 text-center">
                <h2 class="sec-title text-uppercase" style="font-size: 32px; color: #231E41;">LOAN <span class="text-theme">CALCULATOR</span></h2>
                <div style="width: 150px; height: 1px; background: #c0c0c0; margin: 0 auto 10px auto; position: relative;">
                  <div style="width: 80px; height: 2px; background: var(--theme-color); position: absolute; left: 50%; top: -0.5px; transform: translateX(-50%);"></div>
                </div>
              </div>
              
              <p class="text-center mb-4 text-muted" style="font-size: 15px;">We have Properties in these Areas View a list of Featured Properties.</p>
              
              <form id="loanForm" onsubmit="event.preventDefault(); calculateLoan();">
                <div class="input-group mb-3" style="border: 1px solid #eee; border-radius: 4px; overflow: hidden;">
                  <span class="input-group-text border-0 rounded-0" style="background-color: var(--theme-color, #36317A); color: white; width: 60px; justify-content: center;"><i class="fa-solid fa-dollar-sign"></i></span>
                  <input type="number" step="any" class="form-control border-0 rounded-0" style="background-color: #f7f7f7; height: 50px; color: #555;" id="loan_amount" placeholder="Loan Amount" required>
                </div>

                <div class="input-group mb-3" style="border: 1px solid #eee; border-radius: 4px; overflow: hidden;">
                  <span class="input-group-text border-0 rounded-0" style="background-color: var(--theme-color, #36317A); color: white; width: 60px; justify-content: center;"><i class="fa-solid fa-calendar-days"></i></span>
                  <input type="number" class="form-control border-0 rounded-0" style="background-color: #f7f7f7; height: 50px; color: #555;" id="loan_months" placeholder="Months">
                </div>

                <div class="input-group mb-3" style="border: 1px solid #eee; border-radius: 4px; overflow: hidden;">
                  <span class="input-group-text border-0 rounded-0" style="background-color: var(--theme-color, #36317A); color: white; width: 60px; justify-content: center;"><i class="fa-solid fa-calendar"></i></span>
                  <input type="number" class="form-control border-0 rounded-0" style="background-color: #f7f7f7; height: 50px; color: #555;" id="loan_years" placeholder="Years">
                </div>

                <div class="input-group mb-3" style="border: 1px solid #eee; border-radius: 4px; overflow: hidden;">
                  <span class="input-group-text border-0 rounded-0" style="background-color: var(--theme-color, #36317A); color: white; width: 60px; justify-content: center;"><i class="fa-solid fa-percent"></i></span>
                  <input type="number" step="any" class="form-control border-0 rounded-0" style="background-color: #f7f7f7; height: 50px; color: #555;" id="loan_interest" placeholder="Interest Rate" required>
                </div>

                <div class="input-group mb-4" style="border: 1px solid #eee; border-radius: 4px; overflow: hidden;">
                  <span class="input-group-text border-0 rounded-0" style="background-color: var(--theme-color, #36317A); color: white; width: 60px; justify-content: center;"><i class="fa-solid fa-arrow-down"></i></span>
                  <input type="number" step="any" class="form-control border-0 rounded-0" style="background-color: #f7f7f7; height: 50px; color: #555;" id="down_payment" placeholder="Down Payment">
                </div>

                <button type="submit" class="btn w-100 mb-4 rounded-0" style="background-color: #3b366a; color: white; font-weight: 500; text-transform: capitalize; height: 50px; font-size: 16px;">Calculate</button>

                <div class="mb-4">
                  <input type="text" class="form-control rounded-0 text-center" style="border: 1px solid #ddd; height: 50px; font-size: 16px; color: #777; background-color: #fff;" id="monthly_payment" placeholder="Monthly Payment" readonly>
                </div>

                <div class="text-center">
                  <button type="button" class="btn rounded-0" style="background-color: #3b366a; color: white; width: 140px; height: 45px; text-transform: capitalize; font-weight: 400;" onclick="resetCalculator()">Reset</button>
                </div>
              </form>
              
            </div>
          </div>
        </div>
      </div>
    </div>

    <script>
      function calculateLoan() {
          let loanAmount = parseFloat(document.getElementById('loan_amount').value) || 0;
          let months = parseInt(document.getElementById('loan_months').value) || 0;
          let years = parseInt(document.getElementById('loan_years').value) || 0;
          let interestRate = parseFloat(document.getElementById('loan_interest').value) || 0;
          let downPayment = parseFloat(document.getElementById('down_payment').value) || 0;

          let principal = loanAmount - downPayment;
          let totalMonths = (years * 12) + months;
          let monthlyInterest = interestRate / 100 / 12;

          let monthlyPayment = 0;

          if (principal > 0 && totalMonths > 0) {
              if (monthlyInterest > 0) {
                  monthlyPayment = principal * (monthlyInterest * Math.pow(1 + monthlyInterest, totalMonths)) / (Math.pow(1 + monthlyInterest, totalMonths) - 1);
              } else {
                  monthlyPayment = principal / totalMonths;
              }
          }

          if (monthlyPayment > 0) {
              document.getElementById('monthly_payment').value = '$' + monthlyPayment.toFixed(2);
          } else {
              document.getElementById('monthly_payment').value = 'Invalid Input';
          }
      }

      function resetCalculator() {
          document.getElementById('loan_amount').value = '';
          document.getElementById('loan_months').value = '';
          document.getElementById('loan_years').value = '';
          document.getElementById('loan_interest').value = '';
          document.getElementById('down_payment').value = '';
          document.getElementById('monthly_payment').value = '';
      }
    </script>

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
