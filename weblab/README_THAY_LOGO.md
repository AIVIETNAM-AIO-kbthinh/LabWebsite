# Hướng dẫn thay logo ISI Lab

Gói này dùng để thay logo mới vào đúng 2 khu vực đã khoanh trong ảnh:

1. Logo nhỏ trên thanh menu/header.
2. Logo lớn ở phần About, vị trí icon trung tâm trong vòng tròn.

## Cách áp dụng nhanh

1. Copy các file trong thư mục `isilab/` của gói này vào thư mục `isilab/` trong project hiện tại:
   - `assets/isilab-new-logo.png`
   - `assets/logo-new.svg`
   - `css/logo-replace.css`
   - `js/logo-replace.js`

2. Mở file `index.html`, thêm dòng CSS này trong thẻ `<head>` sau file CSS chính:

```html
<link rel="stylesheet" href="css/logo-replace.css">
```

3. Trong `index.html`, thêm dòng JS này ngay trước thẻ đóng `</body>`:

```html
<script src="js/logo-replace.js"></script>
```

4. Lưu lại và mở website. Logo mới sẽ được tự động thay vào 2 vị trí đã khoanh.

## Ghi chú

- File logo mới đã được xử lý nền trong suốt: `assets/isilab-new-logo.png`.
- Script giữ nguyên bố cục cũ, chỉ thay nội dung icon microchip bằng logo mới.
- Nếu project đang dùng tên class khác, vẫn có thể thay trực tiếp bằng cách đặt ảnh sau vào 2 khối icon:

```html
<img src="assets/isilab-new-logo.png" alt="ISI Lab Logo" class="isilab-new-logo-img">
```
