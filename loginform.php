<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="css/loginform.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <div class="box_left">
        <form method="POST" action="login.php" > 
            <h1>เข้าสู่ระบบบัญชีของคุณ</h1><br><br>
            <div class="name">
                <label>ชื่อผู้ใช้</label><br><br>
                <input type="text" name="username"  placeholder="กรุณากรอกชื่อผู้ใช้ของคุณ" required>
                <!-- <i class="fa-solid fa-lock"></i> -->
            </div><br>
            <div class="pass">
                <label>รหัสผ่าน</label><br><br>
                <div style="position: relative; display: flex; align-items: center;">
                    <input type="password" name="password" id="password" placeholder="กรุณากรอกรหัสผ่านของคุณ" required>
                    <i class="fas fa-eye" id="togglePassword" style="position: absolute; right: 15px; cursor: pointer; color:#C2C2C2;"></i>
                </div>
                <!-- <i class="fa-solid fa-lock"></i> -->
            </div><br><br><br>
            <button type="submit">เข้าสู่ระบบ</button><br><br>
            <div class="regis">
                <hr>&nbsp;&nbsp;&nbsp;&nbsp;หากยังไม่มีบัญชี&nbsp;&nbsp;&nbsp;&nbsp;<hr>
            </div><br><br>
            <a href="register.php">สมัครสมาชิก</a>
        </form>
        <?php
            if (isset($_GET['status'])) {
                $status = $_GET['status'];
                $title = 'เกิดข้อผิดพลาด!';
                $text = ''; // Initialize text
                $icon = 'error'; // Default icon for errors
                $redirect = false; // Flag for redirection

                if ($status == 1) {
                    $text = 'ชื่อผู้ใช้งานไม่ถูกต้อง';
                } else if ($status == 2) {
                    $text = 'รหัสผ่านไม่ถูกต้อง';
                } else if ($status == 'registered_successfully') { // Handle success case
                    $title = 'สำเร็จ!';
                    $text = "สมัครสมาชิกสำเร็จแล้ว กรุณาลงชื่อเข้าใช้";
                    $icon = 'success';
                    // No redirect needed here, as the user is already on the login page
                }
                // สามารถเพิ่มเงื่อนไข status อื่นๆ ได้ตามต้องการ

                if (!empty($text)) {
                    echo "<script>";
                    echo "Swal.fire({ title: '$title', text: '$text', icon: '$icon', confirmButtonText: 'ตกลง' });";
                    echo "</script>";
                }
            }
        ?>
    </div>
    <!-- box_left -->
    <div class="box_right">
        <div class="text_box">
            <h1>ยินดีต้อนรับ!</h1>
            <p>ดูแลสุขภาพของคุณ เริ่มต้นที่นี่<br>
            เข้าสู่ระบบเพื่อรับคำแนะนำอาหารที่เหมาะกับคุณ</p>
        </div>
    </div>
    <!-- box_right -->

    <script>
        const togglePassword = document.querySelector('#togglePassword');
        const password = document.querySelector('#password');

        togglePassword.addEventListener('click', function (e) {
            // toggle the type attribute
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            // toggle the eye slash icon
            if (type === 'password') {
                this.classList.remove('fa-eye-slash');
                this.classList.add('fa-eye');
            } else {
                this.classList.remove('fa-eye');
                this.classList.add('fa-eye-slash');
            }
        });
    </script>
</body>
</html>