window.handleGoogleCredential = function (response) {
  if (!response || !response.credential) return;
  var field = document.getElementById('googleCredential');
  var form = document.getElementById('googleLoginForm');
  if (field && form) { field.value = response.credential; form.submit(); }
};(function () {
  'use strict';
  var toggle = document.querySelector('[data-sidebar-toggle]');
  var sidebar = document.getElementById('sidebar');
  if (toggle && sidebar) {
    toggle.addEventListener('click', function () { sidebar.classList.toggle('open'); });
    document.addEventListener('click', function (event) {
      if (window.innerWidth <= 900 && sidebar.classList.contains('open') && !sidebar.contains(event.target) && !toggle.contains(event.target)) {
        sidebar.classList.remove('open');
      }
    });
  }
  var promo = document.querySelector('[data-dismissible-promo]');
  if (promo) {
    var promoKey = 'fkksm-lms-promo-hidden';
    if (localStorage.getItem(promoKey) === '1') promo.hidden = true;
    var dismissPromo = promo.querySelector('.promo-dismiss');
    if (dismissPromo) dismissPromo.addEventListener('click', function () {
      promo.hidden = true;
      localStorage.setItem(promoKey, '1');
    });
  }  var courseSearch = document.getElementById('courseSearch');
  if (courseSearch) {
    courseSearch.addEventListener('input', function () {
      var term = courseSearch.value.toLowerCase();
      document.querySelectorAll('.searchable-card').forEach(function (card) {
        card.hidden = !card.textContent.toLowerCase().includes(term);
      });
    });
  }
  document.querySelectorAll('[data-table-search]').forEach(function (input) {
    input.addEventListener('input', function () {
      var table = document.getElementById(input.getAttribute('data-table-search'));
      if (!table) return;
      var term = input.value.toLowerCase();
      table.querySelectorAll('tbody tr').forEach(function (row) {
        row.hidden = !row.textContent.toLowerCase().includes(term);
      });
    });
  });
  var loginForm = document.querySelector('[data-clear-login="true"]');
  if (loginForm) {
    var loginField = loginForm.querySelector('[name="login"]');
    var passwordField = loginForm.querySelector('[name="password"]');
    var clearLoginFields = function () {
      if (loginField) loginField.value = '';
      if (passwordField) passwordField.value = '';
    };
    clearLoginFields();
    window.addEventListener('pageshow', clearLoginFields);
    window.setTimeout(clearLoginFields, 150);
  }
}());

