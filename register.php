<!DOCTYPE html>
<html>
<head>
  <title>Register as Donor</title>
  <style>
    body { font-family: sans-serif; background: #fefefe; padding: 40px; }
    .container {
      max-width: 500px;
      margin: auto;
      padding: 20px;
      background: #fff5f5;
      border-radius: 10px;
      box-shadow: 0 0 10px #ccc;
    }
    h2 { text-align: center; color: #d00000; }
    label { display: block; margin-top: 12px; }
    input, select {
      width: 100%;
      padding: 10px;
      margin-top: 6px;
      border: 1px solid #ccc;
      border-radius: 6px;
    }
    button {
      margin-top: 20px;
      width: 100%;
      background: #d00000;
      color: white;
      padding: 12px;
      border: none;
      border-radius: 6px;
      cursor: pointer;
    }
  </style>
</head>
<body>
  <div class="container">
    <h2>Donor Registration</h2>
    <form action="send_otp.php" method="POST">
      <label>Name</label>
      <input type="text" name="name" required>

      <label>Email</label>
      <input type="email" name="email" required>

      <label>Password</label>
      <input type="password" name="password" required>

      <label>Phone</label>
      <input type="text" name="phone" required>

      <label>City</label>
      <input type="text" name="city" required>

      <label>Pincode</label>
      <input type="text" name="pincode" required>

      <label>Blood Group</label>
      <select name="blood_group" required>
        <option value="">-- Select --</option>
        <option>A+</option><option>A-</option><option>B+</option><option>B-</option>
        <option>O+</option><option>O-</option><option>AB+</option><option>AB-</option>
      </select>

      <button type="submit">Send OTP</button>
    </form>
  </div>
</body>
</html>
