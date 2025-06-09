<script src="<?php echo BASE_URL; ?>/public/js/script.js"></script>
<script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    const tagsElement = document.querySelector('#tag_ids');
    if (tagsElement) {
      const choices = new Choices(tagsElement, {
        removeItemButton: true, // Hiện nút 'x' để xóa tag đã chọn
        placeholder: true,
        placeholderValue: 'Select tags...',
      });
    }
  });
</script>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    // --- Code của Choices.js đã có ở đây ---
    const tagsElement = document.querySelector('#tag_ids');
    if (tagsElement) {
      // ...
    }

    // --- THÊM ĐOẠN CODE ĐỊNH DẠNG SỐ VÀO ĐÂY ---
    const formattedAmountInput = document.getElementById('formatted-amount');
    const actualAmountInput = document.getElementById('actual-amount');

    if (formattedAmountInput && actualAmountInput) {
        // Hàm để định dạng số (thêm dấu phẩy)
        const formatNumber = (numStr) => {
            if (!numStr) return '';
            return numStr.replace(/\B(?=(\d{3})+(?!\d))/g, ',');
        };

        // Hàm để loại bỏ định dạng (xóa dấu phẩy)
        const unformatNumber = (formattedStr) => {
            if (!formattedStr) return '';
            return formattedStr.replace(/,/g, '');
        };

        // Định dạng giá trị ban đầu (quan trọng cho trang edit)
        formattedAmountInput.value = formatNumber(actualAmountInput.value);

        // Lắng nghe sự kiện input để định dạng lại khi người dùng gõ
        formattedAmountInput.addEventListener('input', () => {
            const rawValue = unformatNumber(formattedAmountInput.value);
            actualAmountInput.value = rawValue; // Cập nhật giá trị cho ô ẩn
            formattedAmountInput.value = formatNumber(rawValue); // Hiển thị lại số đã định dạng
        });
    }
    // --- KẾT THÚC ĐOẠN CODE ĐỊNH DẠNG SỐ ---
  });
</script>
</body>
</html>