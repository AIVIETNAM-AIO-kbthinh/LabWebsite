<?php
/**
 * Faculty of Engineering and Technology (Khoa Kỹ thuật & Công nghệ),
 * VNU International School - staff roster used by the 20/11 page.
 *
 * Source: https://www.is.vnu.edu.vn/category/doi-ngu-giang-vien-khoa-ky-thuat-cong-nghe/
 * (checked 19/11/2026). Order: leadership, Assoc. Prof., PhD, MSc, faculty staff.
 *
 * Keys:
 *   name_vi / name_en     full name without degree
 *   degree_vi / degree_en academic degree / title
 *   role_vi / role_en     position in the school / faculty
 *   profile               official profile page on is.vnu.edu.vn
 *   lab_slug              slug in `members` if the person is also an ISI Lab teacher
 */

$lecturer = ['role_vi' => 'Giảng viên', 'role_en' => 'Lecturer'];

return [
    ['name_vi' => 'Nguyễn Quang Thuận', 'name_en' => 'Nguyen Quang Thuan', 'degree_vi' => 'TS.', 'degree_en' => 'PhD',
     'role_vi' => 'Phó Hiệu trưởng', 'role_en' => 'Vice Rector',
     'profile' => 'https://www.is.vnu.edu.vn/ts-nguyen-quang-thuan/'],

    ['name_vi' => 'Lê Xuân Hải', 'name_en' => 'Le Xuan Hai', 'degree_vi' => 'TS.', 'degree_en' => 'PhD',
     'role_vi' => 'Phó Trưởng khoa phụ trách Khoa', 'role_en' => 'Acting Dean',
     'profile' => 'https://www.is.vnu.edu.vn/ts-le-xuan-hai/'],

    ['name_vi' => 'Nguyễn Như Tùng', 'name_en' => 'Nguyen Nhu Tung', 'degree_vi' => 'PGS.TS.', 'degree_en' => 'Assoc. Prof., PhD']
        + $lecturer + ['profile' => 'https://www.is.vnu.edu.vn/pgs-ts-nguyen-nhu-tung/'],

    ['name_vi' => 'Nguyễn Thanh Tùng', 'name_en' => 'Nguyen Thanh Tung', 'degree_vi' => 'PGS.TS.', 'degree_en' => 'Assoc. Prof., PhD']
        + $lecturer + ['profile' => 'https://www.is.vnu.edu.vn/pgs-ts-nguyen-thanh-tung/'],

    ['name_vi' => 'Phạm Thị Việt Hương', 'name_en' => 'Pham Thi Viet Huong', 'degree_vi' => 'TS.', 'degree_en' => 'PhD']
        + $lecturer + ['profile' => 'https://www.is.vnu.edu.vn/ts-pham-thi-viet-huong/'],

    ['name_vi' => 'Kim Đình Thái', 'name_en' => 'Kim Dinh Thai', 'degree_vi' => 'TS.', 'degree_en' => 'PhD']
        + $lecturer + ['profile' => 'https://www.is.vnu.edu.vn/ts-kim-dinh-thai/'],

    ['name_vi' => 'Phạm Ngọc Thành', 'name_en' => 'Pham Ngoc Thanh', 'degree_vi' => 'TS.', 'degree_en' => 'PhD']
        + $lecturer + ['profile' => 'https://www.is.vnu.edu.vn/pham-ngoc-thanh/'],

    ['name_vi' => 'Phạm Đình Tân', 'name_en' => 'Pham Dinh Tan', 'degree_vi' => 'TS.', 'degree_en' => 'PhD']
        + $lecturer + ['profile' => 'https://www.is.vnu.edu.vn/ts-pham-dinh-tan/'],

    ['name_vi' => 'Nguyễn Thế Anh', 'name_en' => 'Nguyen The Anh', 'degree_vi' => 'TS.', 'degree_en' => 'PhD']
        + $lecturer + ['profile' => 'https://www.is.vnu.edu.vn/ts-nguyen-the-anh/'],

    ['name_vi' => 'Nguyễn Ngọc Linh', 'name_en' => 'Nguyen Ngoc Linh', 'degree_vi' => 'TS.', 'degree_en' => 'PhD']
        + $lecturer + ['profile' => 'https://www.is.vnu.edu.vn/nguyen-ngoc-linh/'],

    ['name_vi' => 'Nguyễn Đăng Khoa', 'name_en' => 'Nguyen Dang Khoa', 'degree_vi' => 'TS.', 'degree_en' => 'PhD']
        + $lecturer + ['profile' => 'https://www.is.vnu.edu.vn/nguyen-dang-khoa/', 'lab_slug' => 'khoa-nguyen-dang'],

    ['name_vi' => 'Lê Duy Tiến', 'name_en' => 'Le Duy Tien', 'degree_vi' => 'TS.', 'degree_en' => 'PhD']
        + $lecturer + ['profile' => 'https://www.is.vnu.edu.vn/ths-le-duy-tien-2/'],

    ['name_vi' => 'Nguyễn Văn Tính', 'name_en' => 'Nguyen Van Tinh', 'degree_vi' => 'TS.', 'degree_en' => 'PhD']
        + $lecturer + ['profile' => 'https://www.is.vnu.edu.vn/nguyen-van-tinh/'],

    ['name_vi' => 'Nguyễn Doãn Đông', 'name_en' => 'Nguyen Doan Dong', 'degree_vi' => 'TS.', 'degree_en' => 'PhD']
        + $lecturer + ['profile' => 'https://www.is.vnu.edu.vn/ts-nguyen-doan-dong/'],

    ['name_vi' => 'Diêm Công Hoàng', 'name_en' => 'Diem Cong Hoang', 'degree_vi' => 'TS.', 'degree_en' => 'PhD']
        + $lecturer + ['profile' => 'https://www.is.vnu.edu.vn/ts-diem-cong-hoang/'],

    ['name_vi' => 'Quách Thái Quyền', 'name_en' => 'Quach Thai Quyen', 'degree_vi' => 'TS.', 'degree_en' => 'PhD']
        + $lecturer + ['profile' => 'https://www.is.vnu.edu.vn/ts-quach-thai-quyen/'],

    ['name_vi' => 'Giáp Văn Tiến', 'name_en' => 'Giap Van Tien', 'degree_vi' => 'TS.', 'degree_en' => 'PhD']
        + $lecturer + ['profile' => 'https://www.is.vnu.edu.vn/ts-giap-van-tien/'],

    ['name_vi' => 'Bùi Thanh Tùng', 'name_en' => 'Bui Thanh Tung', 'degree_vi' => 'ThS.', 'degree_en' => 'MSc']
        + $lecturer + ['profile' => 'https://www.is.vnu.edu.vn/bui-thanh-tung/', 'lab_slug' => 'tung-bui-thanh'],

    ['name_vi' => 'Đỗ Tiến Thành', 'name_en' => 'Do Tien Thanh', 'degree_vi' => 'ThS.', 'degree_en' => 'MSc']
        + $lecturer + ['profile' => 'https://www.is.vnu.edu.vn/ths-do-tien-thanh/'],

    ['name_vi' => 'Phạm Hải Yến', 'name_en' => 'Pham Hai Yen', 'degree_vi' => 'ThS.', 'degree_en' => 'MSc']
        + $lecturer + ['profile' => 'https://www.is.vnu.edu.vn/ths-pham-hai-yen/', 'lab_slug' => 'pham-hai-yen'],

    ['name_vi' => 'Mai Thị Minh Hường', 'name_en' => 'Mai Thi Minh Huong', 'degree_vi' => 'ThS.', 'degree_en' => 'MSc',
     'role_vi' => 'Giáo vụ Khoa', 'role_en' => 'Faculty Academic Affairs',
     'profile' => 'https://www.is.vnu.edu.vn/ths-mai-thi-minh-huong/'],
];
