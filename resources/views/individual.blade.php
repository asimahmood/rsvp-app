<!-- resources/views/rsvp/individual.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Individual RSVP</title>
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
  <h2>Individual RSVP</h2>
  <form id="rsvp-form">
    @csrf
    <label>Full Name*:</label>
    <input type="text" name="name" required />

    <label>Phone Number*:</label>
    <input type="text" name="phone" required />

    <label>Interests (optional):</label>
    <input type="text" name="interests" />

    <button type="submit">RSVP</button>
  </form>

  <div id="qr-code"></div>

  <script>
    document.getElementById('rsvp-form').addEventListener('submit', function(e) {
      e.preventDefault();

      const form = e.target;
      const formData = new FormData(form);

      axios.post('{{ url("/api/rsvp/individual") }}', formData)
        .then(response => {
          const qrImage = response.data.qr_code;
          const message = response.data.message;
          document.getElementById('qr-code').innerHTML = `<h3>${message}</h3><img src="${qrImage}" />`;
        })
        .catch(error => {
          alert('Submission failed. See console for details.');
          console.error(error);
        });
    });
  </script>
</body>
</html>
