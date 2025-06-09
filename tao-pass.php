<?php
// Mật khẩu bạn muốn sử dụng để đăng nhập (giống trong form)
$password = '123456';

// Mã hóa mật khẩu
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// In chuỗi đã mã hóa ra màn hình
echo "Mật khẩu đã mã hóa cho '123456' là: <br>";
echo '<textarea rows="3" cols="80" readonly>' . $hashed_password . '</textarea>';
echo '<p>Hãy sao chép chuỗi trên và dán vào cột "password" trong database của bạn.</p>';
?>