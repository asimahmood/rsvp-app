<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>RSVP as a Couple</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
  <style>
    body {
      font-family: Arial, sans-serif;
      max-width: 600px;
      margin: 40px auto;
      padding: 20px;
      border: 1px solid #ccc;
      border-radius: 10px;
      background-color: #f9f9f9;
    }
    input, button {
      display: block;
      margin: 10px 0;
      padding: 10px;
      width: 100%;
    }
    h2, h3 {
      text-align: center;
    }
    .message {
      text-align: center;
      margin: 10px 0;
      font-weight: bold;
    }
  </style>
</head>
<body>

  <h2>RSVP as a Couple</h2>

  <form id="rsvp-form">
    @csrf

    <label>Your Full Name*:</label>
    <input type="text" name="name" required />

    <label>Your Phone Number*:</label>
    <input type="text" name="phone" required />

    <label>Your Interests (optional):</label>
    <input type="text" name="interests" />

    <label>Partner's Name*:</label>
    <input type="text" name="guests[]" required />

    <button type="submit">RSVP</button>
  </form>

  <div class="message" id="response-message"></div>
  <div id="qr-code"></div>

  <script>
    document.getElementById('rsvp-form').addEventListener('submit', function(e) {
      e.preventDefault();

      const form = e.target;
      const formData = new FormData(form);

      axios.post('{{ url("/api/rsvp") }}', formData)
        .then(response => {
          document.getElementById('response-message').textContent = response.data.message || 'RSVP successful!';
          const qrImage = response.data.qr_code;
          document.getElementById('qr-code').innerHTML = `<h3>Your QR Code:</h3><img src="${qrImage}" />`;
        })
        .catch(error => {
          alert('Submission failed. See console for details.');
          console.error(error);
        });
    });
  </script>

</body>
</html>
