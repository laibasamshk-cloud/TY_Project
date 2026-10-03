<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    // redirect to login.php outside 'new' folder
    header("Location: ../login.php"); 
    exit;
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Wedding Booking</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
     <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
 <style>
    body {
      display: flex;
      flex-direction: column;
      min-height: 50vh;
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
      background-color: #fdf8f9; 
      background-size: cover;
      background-position: center;
      background-repeat: no-repeat;
    }
    .page-wrapper {
      flex: 1;
    }
    header {
      position: fixed;
      top: 0;
      z-index: 999;
      padding: 0 1%;
      background-color: #000000;
      display: flex;
      align-items: center;
      justify-content: space-between;
      width: 100%;
      height: 80px;
    }
    .logo {
      display: flex;
      align-items: center;
      gap: 15px;
    }
    .logo-img {
      height: 70px;
      width: auto;
    }
    header h3 {
      font-size: 30px;
      font-family: "Monsieur La Doulaise", cursive;
      font-style: italic;
      color: rgb(194, 123, 133);
      margin: 0;
    }
    nav ul {
      list-style: none;
      display: flex;
      gap: 3px;
      margin: 0.5rem;
      margin-top: 1.7rem;
      padding: 2rem;
    }
    nav a {
      font-weight: bold;
      color: #fff;
      text-decoration: none;
      font-size: 15px;
      display: flex;
      align-items: center;
      gap: 5px;
      margin-right: 1rem;
    }
    nav ul li a i{
      color: rgb(194, 123, 133);
      font-size: 23px;
    }
    footer {
      background-color: #000;
      color: white;
      padding: 10px 10px 10px;
      text-align: center;
      font-family: 'Georgia', serif;
    }
    .footer-content {
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 8px;
    }
    .footer-logo {
      width: 60px;
      height: auto;
      margin-bottom: 5px;
    }
    .footer-names {
      font-size: 16px;
      font-weight: bold;
      color: #c27b85;
    }
    .footer-locations {
      font-size: 13px;
      color: #ddd;
    }
    .footer-copy {
      font-size: 12px;
      color: #aaa;
      margin-top: 1px;
    }
    body { 
      background: #f8f9fa;
      font-family: Arial, sans-serif; 
    }
    .main-container { 
      display: flex; 
      padding: 70px;
    }
    .form-container { 
      flex: 1; 
      background: #fff; 
      padding-top: 2rem;
      border-radius: 8px; 
      box-shadow: 0 0 8px rgba(0,0,0,0.1);
    }
    .summary { 
      margin-top: 15px; 
      padding: 10px; 
      border: 1px solid #ddd; 
      border-radius: 5px; 
      background: #fafafa; 
    }
    h2 { 
      font-size: 20px; 
      text-align: center; 
      margin-bottom: 15px; 
    }
    .hall-card {
      border: 2px solid #ddd;
      border-radius: 8px;
      padding: 10px;
      text-align: center;
      cursor: pointer;
      transition: 0.3s;
      background: #fff;
    }
    .hall-card img {
      width: 100%;
      height: 230px;
      object-fit: cover;
      border-radius: 6px;
    }
    .hall-card:hover {
      border-color: #c27b85;
      transform: scale(1.03);
    }
    .hall-card.selected {
      border-color: #c27b85;
      box-shadow: 0 0 10px rgba(194, 123, 133, 0.7);
    }
    .alert {
      margin-top: 90px;
    }
    .marquee {
      display: inline-block;
      white-space: nowrap;
      animation: scroll 8s linear infinite alternate;
    }
    @keyframes scroll {
      0%   { transform: translateX(0%); }
      100% { transform: translateX(50%); }
    }

  </style>
</head>  
<body>
  <div class="page-wrapper">
    <header>
      <div class="logo">
        <img src="../images/lai.png" alt="Logo" class="logo-img">
        <h3><i class="fas fa-heart"></i> Petals & Promises</h3>
      </div>
      <nav>
        <ul>
          <li><a href="../demo.html"><i class="fas fa-home"></i>Home</a></li>
          <li><a href="../about.html"><i class="fas fa-heart"></i>About Us</a></li>
          <li><a href="../services.html"><i class="fas fa-concierge-bell"></i>Services</a></li>
          <li><a href="../about.html#blogs"><i class="fas fa-book"></i>Blog</a></li>
          <li><a href="#"><i class="fas fa-user"></i>Booking</a></li>

          <?php if(isset($_SESSION['user_email'])): ?>
      <li><a href="dashboard.php"><i class="fas fa-user"></i>Dashboard</a></li>
      <li><a href="../logout.php"><i class="fas fa-sign-out-alt"></i>Logout</a></li>
    <?php else: ?>
      <li><a href="../login.php"><i class="fas fa-sign-in-alt"></i>Login</a></li>
    <?php endif; ?>

        </ul>
      </nav>
    </header>

    <div class="alert alert-primary text-center" role="alert">
      <div class="marquee">
        💡 Tip: Selecting the <b>Premium/Luxury Decor</b> + <b>Oasis/Celestial Luxe</b> package gives you a discount!
      </div>
    </div>

  

 
<!-- Booking Calendar Section -->
<div class="calendar-section" style="padding: 40px 20px; background-color: #fff; margin: 20px auto; max-width: 1200px; border-radius: 10px; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
    <h2 style="text-align: center; color: #c27b85; font-family: 'Georgia', serif; margin-bottom: 30px;">
        <i class="fas fa-calendar-alt"></i> Hall Availability Calendar
    </h2>
    <p style="text-align: center; margin-bottom: 30px; color: #666;">
        See when our halls are booked. Red dates indicate booked slots. Use navigation to see future dates!
    </p>
    <div id="bookingCalendar" style="height: 600px; margin: 0 auto;"></div>
    <div id="calendarStatus" style="text-align: center; margin-top: 20px; padding: 10px; background-color: #f8f9fa; border-radius: 5px; color: #666;"></div>
</div>

<!-- Add FullCalendar dependencies -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css">
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>

<script>
$(document).ready(function() {
    console.log("Document ready - loading calendar");
    
    $('#calendarStatus').text('Loading calendar...');
    
    var calendarEl = document.getElementById('bookingCalendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        initialDate: '2024-12-01', // Start from December 2024 where we have bookings
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        height: 'auto',
        events: function(fetchInfo, successCallback, failureCallback) {
            console.log("Fetching events for period:", fetchInfo.startStr, "to", fetchInfo.endStr);
            
            $.ajax({
                url: '../admin/get_public_bookings.php',
                type: 'POST',
                data: { action: 'get_public_bookings' },
                dataType: 'json',
                success: function(response) {
                    console.log("Full response:", response);
                    
                    if (response.success) {
                        console.log("Events received:", response.events);
                        console.log("Number of events:", response.events.length);
                        
                        // Update status with count
                        $('#calendarStatus').text('Calendar loaded! Found ' + response.events.length + ' booked dates.');
                        
                        // Pass the events to the calendar
                        successCallback(response.events);
                    } else {
                        console.error("Error in response:", response.error);
                        $('#calendarStatus').text('Error: ' + response.error);
                        failureCallback(new Error(response.error));
                    }
                },
                error: function(xhr, status, error) {
                    console.error("AJAX Error:", error);
                    console.log("Response text:", xhr.responseText);
                    $('#calendarStatus').text('Failed to load calendar data: ' + error);
                    failureCallback(error);
                }
            });
        },
        eventDidMount: function(info) {
            // Add tooltip
            info.el.setAttribute('title', 
                info.event.extendedProps.hall_name + ' - ' + 
                (info.event.extendedProps.time_slot === 'day' ? 'Day Time' : 'Evening Time')
            );
            
            // Style the event
            info.el.style.backgroundColor = '#dc3545';
            info.el.style.borderColor = '#dc3545';
            info.el.style.color = 'white';
            info.el.style.padding = '2px 4px';
            info.el.style.fontSize = '11px';
            info.el.style.borderRadius = '3px';
            info.el.style.margin = '1px 0';
            info.el.style.cursor = 'pointer';
        },
        dateClick: function(info) {
            if (confirm('Do you want to book on ' + info.dateStr + '?')) {
                $('input[name="booking_date"]').val(info.dateStr);
                alert('Date selected! Please complete the booking form above.');
            }
        }
    });
    
    calendar.render();
    console.log("Calendar rendered");
    
    // List months with bookings for user guidance
    setTimeout(function() {
        $('#calendarStatus').html('Calendar loaded! Use the navigation buttons to browse months.<br>' +
            '<small>Bookings exist in: Dec 2024, Oct 2025, May 2026, Jun 2026, Oct 2026, Apr 2027, Sep 2027, Sep 2028, Sep 2037, Sep 2038</small>');
    }, 1500);
});
</script>

<style>
/* Calendar styling */
.fc {
    font-family: 'Arial', sans-serif;
}
.fc .fc-toolbar-title {
    color: #c27b85;
    font-size: 1.5em;
    font-weight: bold;
}
.fc .fc-button-primary {
    background-color: #c27b85;
    border-color: #c27b85;
}
.fc .fc-button-primary:hover {
    background-color: #a95c68;
    border-color: #a95c68;
}
.fc .fc-button-primary:not(:disabled):active,
.fc .fc-button-primary:not(:disabled).fc-button-active {
    background-color: #a95c68;
    border-color: #a95c68;
}
.fc .fc-daygrid-day-number {
    color: #333;
    font-weight: 500;
}
.fc .fc-event {
    cursor: pointer;
    font-size: 11px;
    padding: 2px 4px;
    margin: 1px 0;
    border-radius: 3px;
}
.fc-day-today {
    background-color: #f8f9fa !important;
}
.fc-daygrid-day.fc-day-today {
    background-color: #f8f9fa !important;
}
/* Highlight booked days */
.fc-daygrid-day.fc-day-booked {
    background-color: #fff3f3;
}
#calendarStatus {
    font-size: 14px;
    padding: 10px;
    background-color: #f8f9fa;
    border-radius: 5px;
    margin-top: 20px;
    border-left: 4px solid #c27b85;
}
</style>




    <div class="main-container">
      <div class="form-container">
        <h2 style="font-weight: bold;">Book Wedding Package</h2>
        <form action="booking.php" id="bookingForm" method="post">
          <label>Name:</label>
          <input type="text" name="name" class="form-control" required>

          <!--<label>Email:</label>
          <input type="email" name="email" class="form-control" required>-->
          
          <label>Contact:</label>
          <input type="text" name="contact" class="form-control" required>
          <label style="font-weight: bold;">Halls</label>
          <a href="halls.html">
            <input type="button" value="Click for Details" style="margin-top: 5px; background-color: rgb(116, 184, 15); font-weight: bold;">
          </a>
          <div class="hall-options row">
            <div class="col-md-3">
              <div class="hall-card" data-name="Oasis Lawn" data-price="800000">
                <img src="../images/oasis.jpg" alt="Oasis Lawn">
                <h6>Oasis Lawn</h6>
                <p>₹8,00,000</p>
              </div>
            </div>
            <div class="col-md-3">
              <div class="hall-card" data-name="Garden Fate" data-price="400000">
                <img src="../images/gardenfate.jpg" alt="Garden Fate">
                <h6>Garden Fate</h6>
                <p>₹4,00,000</p>
              </div>
            </div>
            <div class="col-md-3">
              <div class="hall-card" data-name="Melody Lawn" data-price="600000">
                <img src="../images/melody.jpg" alt="Melody Lawn">
                <h6>Melody Lawn</h6>
                <p>₹6,00,000</p>
              </div>
            </div>
            <div class="col-md-3">
              <div class="hall-card" data-name="Celestial Luxe" data-price="1000000">
                <img src="../images/celestial.jpg" alt="Celestial Luxe">
                <h6>Celestial Luxe</h6>
                <p>₹10,00,000</p>
              </div>
            </div>
          </div>
          <input type="hidden" name="hall_name" id="hall">
          <label>Date:</label>
          <input type="date" name="booking_date" class="form-control" required>
          <label>Time Slot:</label>
          <select name="time_slot" class="form-control" required>
            <option value="day">Day Time</option>
            <option value="evening">Evening Time</option>
          </select>
          <label>Catering:</label>
          <label>No. of Plates:</label>
          <input type="number" id="plates" name="plates" class="form-control" min="1" value="0"><br>
          <h3 style="text-align: center; font-weight: bold;">Menu</h3>
          <div id="cateringMenu" class="row mt-4">
            <div class="col-md-6">
              <h6>Veg Menu</h6>
              <div>
                <input type="checkbox" class="menu-item" data-name="Paneer Butter Masala" data-price="220">
                Paneer Butter Masala - ₹220 per plate
              </div>
              <div>
                <input type="checkbox" class="menu-item" data-name="Veg Biryani" data-price="200">
                Veg Biryani - ₹200 per plate
              </div>
              <div>
                <input type="checkbox" class="menu-item" data-name="Chappati" data-price="15">
                Chappati - ₹15 per 
              </div>
              <div>
                <input type="checkbox" class="menu-item" data-name="Veg Korma" data-price="180">
                Veg Korma - ₹180 per plate
              </div>
              <h6 class="mt-2">Non-Veg Menu</h6>
              <div>
                <input type="checkbox" class="menu-item" data-name="Chicken Biryani" data-price="350">
                Chicken Biryani - ₹350 per plate
              </div>
              <div>
                <input type="checkbox" class="menu-item" data-name="Butter Chicken" data-price="300">
                Butter Chicken - ₹300 per plate
              </div>
              <div>
                <input type="checkbox" class="menu-item" data-name="Fish Fry" data-price="400">
                Fish Fry - ₹400 per plate
              </div>
              <h6 class="mt-2">Starters</h6>
              <div>
                <input type="checkbox" class="menu-item" data-name="Chicken Roll" data-price="180">
                Chicken Roll - ₹180 per plate(2 peices)
              </div>
              <div>
                <input type="checkbox" class="menu-item" data-name="Veg Spring Roll" data-price="125">
                Veg Spring Roll - ₹125 per plate(2 peices)
              </div>
              <div>
                <input type="checkbox" class="menu-item" data-name="Chicken Lollipop" data-price="150">
                Chicken Lollipop - ₹150 per plate(4 peices)
              </div>
              <div>
                <input type="checkbox" class="menu-item" data-name="Veg Dry Manchurian" data-price="100">
                Veg Dry Manchurian - ₹100 per plate
              </div>
            </div>
            <div class="col-md-3">
              <h6>Drinks & Beverages</h6>
              <div>
                <input type="checkbox" class="menu-item" data-name="Soft Drink" data-price="60">
                Soft Drink - ₹60 per glass
              </div>
              <div>
                <input type="checkbox" class="menu-item" data-name="Orange Juice" data-price="80">
                Orange Juice - ₹80 per glass
              </div>
              <div>
                <input type="checkbox" class="menu-item" data-name="Citrus Juice" data-price="80">
                Citrus Juice - ₹80 per glass
              </div>
              <div>
                <input type="checkbox" class="menu-item" data-name="Mocktail" data-price="100">
                Mocktail - ₹100 per glass
              </div>
              <div>
                <input type="checkbox" class="menu-item" data-name="Coffee" data-price="40">
                Coffee - ₹40 per cup
              </div>
              <h6 class="mt-2">Desserts</h6>
              <div>
                <input type="checkbox" class="menu-item" data-name="Gulab Jamun" data-price="60">
                Gulab Jamun - ₹60 per plate(2 peices)
              </div>
              <div>
                <input type="checkbox" class="menu-item" data-name="Ice Cream" data-price="100">
                Ice Cream - ₹100 per plate
              </div>
              <div>
                <input type="checkbox" class="menu-item" data-name="Pastry" data-price="80">
                Pastry - ₹80 per plate
              </div>
              <h6 class="mt-2">Extra</h6>
              <div>
                <input type="checkbox" class="menu-item" data-name="Pani Puri" data-price="50">
                Pani Puri - ₹50 per plate
              </div>
              <div>
                <input type="checkbox" class="menu-item" data-name="Fruit Salad" data-price="70">
                Fruit Salad - ₹70 per plate
              </div>
              <div>
                <input type="checkbox" class="menu-item" data-name="Tea" data-price="30">
                Tea - ₹30 per cup
              </div>
            </div>
          </div>
          <br>
          <label>Decoration:</label>
          <select name="decoration" id="decoration" class="form-control" required>
            <option value="">--Select Decoration--</option>
            <option value="Basic" data-price="60000">Basic - ₹60,000</option>
            <option value="Premium" data-price="90000">Premium - ₹90,000</option>
            <option value="Luxury" data-price="120000">Luxury - ₹1,20,000</option>
          </select>
          <div class="summary">
            <h5>Booking Summary</h5>
            <p id="summaryText">Please select options.</p>
            <h5>Total: ₹<span id="total">0</span></h5>
          </div>
          <button type="submit" class="btn btn-success btn-block">Confirm Booking</button>
        </form>
        <p id="message"></p>
      </div>
    </div>



<script>




      function updateTotal() {
        let hallPrice = parseInt($('#hall').data('price')) || 0;
        let plates = parseInt($('#plates').val()) || 0;
        let cateringTotal = 0;
        let selectedItems = [];
        $('.menu-item:checked').each(function(){
          let price = parseInt($(this).data('price')) || 0;
          let name = $(this).data('name');
          cateringTotal += price * plates;
          selectedItems.push(name + " (₹" + price + ")");
        });
        let decorationPrice = parseInt($('#decoration option:selected').data('price')) || 0;
        let total = hallPrice + cateringTotal + decorationPrice;
        $('#total').text(total);
        $('#summaryText').html(
          "Hall: " + ($('#hall').val() || "None") + " (₹" + hallPrice + ")<br>" +
          "Catering: " + (selectedItems.length > 0 ? selectedItems.join(", ") : "None") +
          " × " + plates + " plates = ₹" + cateringTotal + "<br>" +
          "Decoration: " + $('#decoration').val()
        );
      }
      $('.hall-card').click(function () {
        $('.hall-card').removeClass('selected');
        $(this).addClass('selected');
        let hallName = $(this).data('name');
        let hallPrice = $(this).data('price');
        $('#hall').val(hallName);
        $('#hall').data('price', hallPrice);
        updateTotal();
      });
      $('#decoration, #plates').on('change keyup', updateTotal);
      $('.menu-item').change(updateTotal);







     $('#bookingForm').submit(function(e){
    e.preventDefault();
    // Validate date before submission
    var bookingDate = $('input[name="booking_date"]').val();
    var currentDate = new Date().toISOString().split('T')[0];
    if (!bookingDate || bookingDate <= currentDate) {
        $('#message').html('<span style="color:red">Please select a valid date after today.</span>');
        return;
    }
    let total = $('#total').text();
    let cateringItems = [];
    $('.menu-item:checked').each(function(){
        cateringItems.push($(this).data('name'));
    });
    $.ajax({
        url: 'booking.php',
        type: 'POST',
        data: $(this).serialize() + "&catering_item=" + cateringItems.join(", ") + "&total_amount=" + total,
        success: function(response){
            $('#message').html(response);
            if(response.includes("Booking Confirmed")) {
                if($('#dashboardBtn').length === 0){
                    $('#message').append('<br><br><button id="dashboardBtn" class="btn btn-info">Go to Dashboard</button>');
                    $('#dashboardBtn').click(function(){
                        window.location.href = 'dashboard.php';
                    });
                }
            }
        }
    });
});

    </script>
      <h4 style="text-align:center;padding-bottom:1rem;color: #443aacff;"><i class="fas fa-bell" style="color:orange;"></i><i style="color:#000; font-weight: bolder;">Note:</i> If any changes or updation in the booking do contact us on +91 9139578601 | +91 9579958675</h3>
    <footer>
      <div class="footer-content">
        <img src="../images/lai.png" alt="LP Logo" class="footer-logo">
        <div class="footer-names"><i class="fas fa-heart"></i> A Touch of Timeless Charm by Laiba & Punita <i class="fas fa-heart"></i></div>
        <div class="footer-locations">Pune | Mumbai | Nashik | Ahmednagar</div>
        <div class="footer-copy">&copy; 2025 LP Petals & Promises. All rights reserved.</div>
      </div>
    </footer> 
  </div>
</body>
</html>
