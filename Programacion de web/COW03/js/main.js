// Add a class to indicate that JavaScript is enabled
document.documentElement.classList.add('js-enabled');

document.observe("dom:loaded", function() {
  console.log("dom:loaded fired");

  // Hide the reservation form container on page load
  var reservationFormContainer = $('reservationFormContainer');
  if (reservationFormContainer) {
    reservationFormContainer.setStyle({ display: 'none' });
  } else {
    console.error("Reservation form container not found");
  }

  // Global function to display the reservation form (called by Book Now buttons)
  window.displayBookingInfo = function(roomType) {
    if (reservationFormContainer) {
      reservationFormContainer.setStyle({ display: 'block' });
      var roomTypeSelect = $('room-type');
      if (roomTypeSelect) {
        roomTypeSelect.value = roomType;
      }
      reservationFormContainer.scrollTo();
      // Force focus on the full name input when form is shown
      var nameInput = $('name');
      if (nameInput) {
        nameInput.focus();
      }
    }
  };

  // Sidebar toggle event listener using Prototype's Event.observe
  var sidebarToggle = $('sidebarToggle');
  var sidebar = $('sidebar');
  if (sidebarToggle && sidebar) {
    Event.observe(sidebarToggle, 'click', function(e) {
      Event.stop(e); // Prevent default anchor behavior
      sidebar.toggleClassName('active');
      document.body.toggleClassName('active-sidebar');
    });
  }

  // Country change event listener to fetch cities via Ajax
  var countrySelect = $('country');
  if (countrySelect) {
    Event.observe(countrySelect, 'change', function() {
      var selectedCountryCode = countrySelect.value;
      var citySelect = $('city');
      if (!selectedCountryCode) {
        citySelect.update('<option value="">-- Select a country first --</option>');
        return;
      }
      var xhr = new XMLHttpRequest();
      xhr.open('GET', 'getCities.php?countryCode=' + encodeURIComponent(selectedCountryCode));
      xhr.onload = function() {
        if (xhr.status === 200) {
          var data = JSON.parse(xhr.responseText);
          citySelect.update('<option value="">Select a city</option>');
          data.forEach(function(cityName) {
            var opt = new Element('option', { value: cityName }).update(cityName);
            citySelect.insert(opt);
          });
        } else {
          console.error("Error loading cities: " + xhr.status);
        }
      };
      xhr.send();
    });
  }

  // Validate Full Name using regex (only letters and spaces)
  var nameInput = $('name');
  if (nameInput) {
    console.log("Full name input found");
    Event.observe(nameInput, 'blur', function() {
      var value = nameInput.getValue();
      var regex = /^[A-Za-z\s]+$/;
      if ($('nameError')) { $('nameError').remove(); }
      if (!regex.test(value)) {
        nameInput.addClassName('is-invalid');
        var errorMsg = new Element('div', { id: 'nameError', className: 'invalid-feedback' });
        errorMsg.update('Not valid name');
        nameInput.insert({ after: errorMsg });
        setTimeout(function() {
          new Effect.Fade('nameError', { duration: 2.0 });
        }, 2000);
      } else {
        nameInput.removeClassName('is-invalid');
      }
    });
  } else {
    console.error("Full name input not found");
  }

  // Validate Email using regex 
  var emailInput = $('email');
  if (emailInput) {
    Event.observe(emailInput, 'blur', function() {
      var value = emailInput.getValue();
      var regex = /^\S+@\S+\.\S+$/;
      if ($('emailError')) { $('emailError').remove(); }
      if (!regex.test(value)) {
        emailInput.addClassName('is-invalid');
        var errorMsg = new Element('div', { id: 'emailError', className: 'invalid-feedback' });
        errorMsg.update('Not a valid email address');
        emailInput.insert({ after: errorMsg });
        setTimeout(function() {
          new Effect.Fade('emailError', { duration: 2.0 });
        }, 2000);
      } else {
        emailInput.removeClassName('is-invalid');
      }
    });
  }
  // Handle reservation form submission with a loading bar effect and Ajax request
  var reservationForm = $('reservationForm');
  if (reservationForm) {
    Event.observe(reservationForm, 'submit', function(e) {
      Event.stop(e); // Prevent the default form submission


    var loadingBarContainer = $('loadingBarContainer');
    if (loadingBarContainer) {
      loadingBarContainer.setStyle({ display: 'block' });
    }
    // Define an array of random tips to display on the loading bar
    var tips = [
      "running away with your money now :DDDDD",
      "All your base are belong to us",
      "running out of shit to say here >.>",
      "i hope comics sans hurts your soul >:D"
    ];
    var randomTip = tips[Math.floor(Math.random() * tips.length)];
    var loadingTip = $('loadingTip');
    if (loadingTip) {
      loadingTip.update(randomTip);
    }
    // Animate the loading bar from 0% to 100% over 5 seconds using Scriptaculous Effect.Morph
    new Effect.Morph('loadingBar', {
      style: 'width: 100%',
      duration: 5.0,
      afterFinish: function() {
        // After the loading animation, submit the form via Ajax
        new Ajax.Request('server.php', {
          method: 'post',
          parameters: Form.serialize(reservationForm),
          onSuccess: function(transport) {
            var response;
            try {
              response = transport.responseText.evalJSON();
            } catch (ex) {
              console.error("Error al parsear el JSON de la reserva: " + ex);
              return;
            }
            if (response.status === "success") {
              // Determine the hotel image based on the room type selected
              var roomType = response.reservation.room_type;
              var hotelImageSrc = "";
              if (roomType === "Single Room") {
                hotelImageSrc = "room1.jpg";
              } else if (roomType === "Double Room") {
                hotelImageSrc = "room2.jpg";
              } else if (roomType === "Suite Room") {
                hotelImageSrc = "room3.jpg";
              }
              // Update the hotel image in the modal 
              var modalImg = jQuery('#modalHotelImage img');
              if (modalImg.length) {
                modalImg.attr("src", hotelImageSrc);
              }
              // Build the HTML containing the reservation details
              var detailsHtml = "<ul>";
              detailsHtml += "<li>Name: " + response.reservation.name + "</li>";
              detailsHtml += "<li>Email: " + response.reservation.email + "</li>";
              detailsHtml += "<li>Room Type: " + response.reservation.room_type + "</li>";
              detailsHtml += "<li>Check-In: " + response.reservation.checkin + "</li>";
              detailsHtml += "<li>Check-Out: " + response.reservation.checkout + "</li>";
              if (response.reservation.country_code) {
                detailsHtml += "<li>Country Code: " + response.reservation.country_code + "</li>";
              }
              if (response.reservation.city) {
                detailsHtml += "<li>City: " + response.reservation.city + "</li>";
              }
              detailsHtml += "</ul>";
              // Update the modal with the reservation details
              jQuery('#modalReservationDetails').html(detailsHtml);
              // Hide the reservation form
              reservationForm.setStyle({ display: 'none' });
              // Show the confirmation modal using Bootstrap 
              jQuery('#reservationModal').modal('show');
            } else {
              console.error("Reservation error: " + response.message);
              alert("Error: " + response.message);
            }
          },
          onFailure: function() {
            console.error("Ajax request for reservation failed");
          }
        });
      }
    });
  });
}


  // The full name input (id "name") and its autocomplete container (id "nameAutocomplete")
var autocompleteContainer = $('nameAutocomplete');
if (nameInput && autocompleteContainer) {
  console.log("Setting up Ajax autocomplete with Bootstrap dropdown");
  
  // Function to update autocomplete suggestions via Ajax
  var updateAutocompleteAjax = function() {
    var query = nameInput.getValue().strip();
    console.log("Ajax autocomplete query: " + query);
    if (query.length === 0) {
      // Remove the show class and update inline style to hide the dropdown
      jQuery(autocompleteContainer).removeClass('show').css("display", "none");
      return;
    }
    new Ajax.Request('getNames.php', {
      method: 'get',
      parameters: { q: query },
      onSuccess: function(transport) {
        console.log("Ajax response: " + transport.responseText);
        var response;
        try {
          response = transport.responseText.evalJSON();
        } catch (ex) {
          console.error("Error parsing JSON: " + ex);
          jQuery(autocompleteContainer).removeClass('show').css("display", "none");
          return;
        }
        if (!response || response.length === 0) {
          console.log("No matching names found.");
          jQuery(autocompleteContainer).removeClass('show').css("display", "none");
          return;
        }
        var html = '';
        response.each(function(name) {
          html += '<a class="dropdown-item autocomplete-item" href="#">' + name + '</a>';
        });
        autocompleteContainer.update(html);
        // Add the show class and update inline style to display the dropdown
        jQuery(autocompleteContainer).addClass('show').css("display", "block");
        // Attach click events to suggestions using jQuery
        jQuery(autocompleteContainer).find('.autocomplete-item').on('click', function(e) {
          e.preventDefault();
          nameInput.value = jQuery(this).text();
          jQuery(autocompleteContainer).removeClass('show').css("display", "none");
        });
      },
      onFailure: function() {
        console.error("Ajax request failed for autocomplete");
        jQuery(autocompleteContainer).removeClass('show').css("display", "none");
      }
    });
  };

  // Observe keyup and focus events on the full name input
  Event.observe(nameInput, 'keyup', function() {
    console.log("Keyup event fired on full name input");
    updateAutocompleteAjax();
  });
  Event.observe(nameInput, 'focus', function() {
    console.log("Focus event fired on full name input");
    updateAutocompleteAjax();
  });
  
  // Hide autocomplete if clicking outside the input or suggestions
  document.observe("click", function(event) {
    if (!Event.element(event).descendantOf(nameInput) &&
        !Event.element(event).descendantOf(autocompleteContainer)) {
      jQuery(autocompleteContainer).removeClass('show').css("display", "none");
    }
  });
} else {
  console.error("Autocomplete container or full name input not found for Ajax version.");
}
});
