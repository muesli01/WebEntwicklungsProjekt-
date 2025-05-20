$("#registrationForm").on("submit", function(e) {
  e.preventDefault();

  // Prüfen, ob die Passwörter übereinstimmen
  if ($("#password").val() !== $("#password_confirm").val()) {
    $("#result").text("Die Passwörter stimmen nicht überein!");
    return;
  }

  // Registrierung per AJAX absenden
  $.ajax({
    url: "../../Backend/logic/registerHandler.php",
    type: "POST",
    data: {
      anrede: $("#anrede").val(),
      vorname: $("#vorname").val(),
      nachname: $("#nachname").val(),
      adresse: $("#adresse").val(),
      plz: $("#plz").val(),
      ort: $("#ort").val(),
      email: $("#email").val(),
      username: $("#username").val(),
      password: $("#password").val(),
      zahlung: $("#zahlung").val()
    },
    dataType: "json",
    success: function(response) {
      $("#result").text(response.message);
    },
    error: function() {
      $("#result").text("Ein Fehler ist aufgetreten.");
    }
  });
});
