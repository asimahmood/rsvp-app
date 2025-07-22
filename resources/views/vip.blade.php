<!-- resources/views/rsvp/vip.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>VIP RSVP</title>
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
  </style>
</head>
<body>
  <h2>VIP RSVP</h2>
  <form id="rsvp-form">
    @csrf
    <label>Full Name*:</label>
    <input type="text" name="name" required />

    <label>Phone Number*:</label>
    <input type="text" name="phone" required />

    <label>Interests (optional):</label>
    <input type="text" name="interests" />

    <h4>Sub Guests</h4>
    <div id="additional-guests"></div>
    <button type="button" onclick="addGuestField()">Add Guest</button><br/>

    <button type="submit">RSVP</button>
  </form>

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

      axios.post('{{ url("/api/rsvp/vip") }}', formData)
        .then(response => {
          const data = response.data;
          if (data.message) {
            document.getElementById('qr-code').innerHTML = `<h3>${data.message}</h3>`;
          }
          if (data.qr_code) {
            document.getElementById('qr-code').innerHTML += `<img src="${data.qr_code}" />`;
          }
        })
        .catch(error => {
          alert('Submission failed. See console for details.');
          console.error(error);
        });
    });
  </script>
</body>
</html>