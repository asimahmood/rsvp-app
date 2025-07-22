<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>RSVP for Event</title>
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
    #message {
      text-align: center;
      font-weight: bold;
      margin-top: 15px;
      color: green;
    }
    #qr-code {
      text-align: center;
      margin-top: 20px;
    }
  </style>
</head>
<body>

  <h2>RSVP for Our Event</h2>

  <form id="rsvp-form">
    @csrf

    <label>Full Name*:</label>
    <input type="text" name="name" required />

    <label>Phone Number*:</label>
    <input type="text" name="phone" required />

    <label>Interests (optional):</label>
    <input type="text" name="interests" />

    <h4>Additional Guests</h4>
    <div id="additional-guests"></div>
    <button type="button" onclick="addGuestField()">Add Guest</button><br/>

    <button type="submit">RSVP</button>
  </form>

  <div id="message"></div>
  <div id="qr-code"></div>

  <script>
    function addGuestField() {
      const div = document.createElement('div');
      div.innerHTML = `Guest Name: <input type="text" name="guests[]" /><br/>`;
      document.getElementById('additional-guests').appendChild(div);
    }

    document.getElementById('rsvp-form').addEventListener('submit', function(e) {
      e.preventDefault();

      const form = e.target;
      const formData = new FormData(form);

      axios.post('{{ url("/api/rsvp") }}', formData)
        .then(response => {
          const data = response.data;

          // Display message if exists
          if (data.message) {
            document.getElementById('message').innerText = data.message;
          }

          //  Display QR code if exists
          if (data.qr_code) {
            document.getElementById('qr-code').innerHTML = `<h3>Your QR Code:</h3><img src="${data.qr_code}" />`;
          }
        })
        .catch(error => {
          document.getElementById('message').innerText = 'Submission failed. Please try again.';
          document.getElementById('message').style.color = 'red';
          console.error(error);
        });
    });
  </script>

</body>
</html>
