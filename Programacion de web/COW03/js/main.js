// Indicate that JavaScript is enabled (so .js-enabled CSS rules apply)
document.documentElement.classList.add('js-enabled');

document.addEventListener("DOMContentLoaded", function() {
  // Hide the reservation form container on page load
  const reservationFormContainer = document.getElementById('reservationFormContainer');
  if (reservationFormContainer) {
    reservationFormContainer.style.display = 'none';
  }

  // Make displayBookingInfo globally accessible
  window.displayBookingInfo = function(roomType) {
    if (reservationFormContainer) {
      reservationFormContainer.style.display = 'block';
      document.getElementById('room-type').value = roomType;
      reservationFormContainer.scrollIntoView({ behavior: 'smooth' });
    }
  };

  // Sidebar toggle event listener
  const sidebarToggle = document.getElementById('sidebarToggle');
  const sidebar = document.getElementById('sidebar');
  if (sidebarToggle && sidebar) {
    sidebarToggle.addEventListener('click', function(e) {
      // Prevent the default anchor link from jumping to #sidebar
      e.preventDefault();
      
      // Toggle the .active class on the sidebar itself
      sidebar.classList.toggle('active');
      // Toggle the .active-sidebar class on body (shifts main content, moves button)
      document.body.classList.toggle('active-sidebar');
    });
  }

  // Country change event listener to fetch cities
  const countrySelect = document.getElementById('country');
  if (countrySelect) {
    countrySelect.addEventListener('change', function() {
      const selectedCountryCode = this.value;
      const citySelect = document.getElementById('city');
      if (!selectedCountryCode) {
        citySelect.innerHTML = '<option value="">-- Select a country first --</option>';
        return;
      }

      const xhr = new XMLHttpRequest();
      xhr.open('GET', 'getCities.php?countryCode=' + encodeURIComponent(selectedCountryCode));
      xhr.onload = function() {
        if (xhr.status === 200) {
          const data = JSON.parse(xhr.responseText);
          citySelect.innerHTML = '<option value="">Select a city</option>';
          data.forEach(function(cityName) {
            const opt = document.createElement('option');
            opt.value = cityName;
            opt.textContent = cityName;
            citySelect.appendChild(opt);
          });
        } else {
          console.error("Error loading cities: " + xhr.status);
        }
      };
      xhr.send();
    });
  }
});
