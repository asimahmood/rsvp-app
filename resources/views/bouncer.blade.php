<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Bouncer QR Scanner</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
  <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
  <style>
    body { font-family: Arial; max-width: 600px; margin: 20px auto; text-align: center; }
    #result { margin-top: 20px; padding: 10px; border: 1px solid #ccc; border-radius: 8px; }
    #qr-reader { width: 100%; margin-bottom: 20px; }
    button { padding: 10px 20px; margin-top: 10px; }
  </style>
</head>
<body>
  <h2>Bouncer Check-in Panel</h2>
  <div id="qr-reader"></div>
  <div id="result"></div>

  <script>
    function showGuestInfo(data) {
      const guestInfo = `
        <strong>Name:</strong> ${data.name}<br>
        <strong>Phone:</strong> ${data.phone}<br>
        <strong>Status:</strong> ${data.checked_in ? '✅ Already Checked-in' : '🟡 Not Checked-in'}<br>
        <strong>Guests:</strong><br>
        <ul>${data.guests.map(name => `<li>${name}</li>`).join('')}</ul>
        ${!data.checked_in ? '<button onclick="markEntry(\'' + data.qr_token + '\')">Mark Entry</button>' : ''}
      `;
      document.getElementById('result').innerHTML = guestInfo;
    }

    function markEntry(token) {
      axios.post(`/api/mark-entry/${token}`)
        .then(() => {
          alert('Entry marked ✅');
          location.reload();
        })
        .catch(err => {
          alert('Error marking entry');
        });
    }

    function onScanSuccess(decodedText, decodedResult) {
      html5QrcodeScanner.clear();
      axios.get(`/api/validate-qr/${decodedText}`)
        .then(res => {
          res.data.qr_token = decodedText;
          showGuestInfo(res.data);
        })
        .catch(() => {
          document.getElementById('result').innerHTML = '<span style="color:red">❌ Invalid QR Code</span>';
        });
    }

    const html5QrcodeScanner = new Html5QrcodeScanner(
      "qr-reader", { fps: 10, qrbox: 250 }
    );
    html5QrcodeScanner.render(onScanSuccess);
  </script>
</body>
</html>
