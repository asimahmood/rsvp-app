<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Panel - Guest List</title>
  <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
  <style>
    body { font-family: Arial; max-width: 900px; margin: 40px auto; }
    h2 { text-align: center; }
    table { width: 100%; border-collapse: collapse; margin-top: 20px; }
    th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
    th { background: #f4f4f4; }
    tr.checked-in { background-color: #e0ffe0; }
    tr.not-checked-in { background-color: #ffe0e0; }
  </style>
</head>
<body>
  <h2>Admin Panel - Guest QR Assignments</h2>
  <table id="guest-table">
    <thead>
      <tr>
        <th>Name</th>
        <th>Phone</th>
        <th>Sub Guests</th>
        <th>QR Token</th>
        <th>Checked In</th>
      </tr>
    </thead>
    <tbody></tbody>
  </table>

  <script>
    axios.get('/api/guests')
      .then(res => {
        const table = document.querySelector('#guest-table tbody');
        res.data.forEach(guest => {
          const row = document.createElement('tr');
          row.className = guest.checked_in ? 'checked-in' : 'not-checked-in';
          row.innerHTML = `
            <td>${guest.name}</td>
            <td>${guest.phone}</td>
            <td>
              <ul>${guest.sub_guests.map(g => `<li>${g.name}</li>`).join('')}</ul>
            </td>
            <td>${guest.qr_token}</td>
            <td>${guest.checked_in ? '✅' : '❌'}</td>
          `;
          table.appendChild(row);
        });
      })
      .catch(err => {
        alert('Failed to load guests.');
        console.error(err);
      });
  </script>
</body>
</html>
