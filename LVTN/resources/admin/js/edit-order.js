document.getElementById('print-order').addEventListener('click', function () {
    const currentDate = new Date();
    const day = currentDate.getDate();
    const month = currentDate.getMonth() + 1;
    const year = currentDate.getFullYear();

    const printContents = `
        <style>
            body {
                font-weight: 400;
                font-size: 12px;
            }

            .info {
                font-weight: 500;
                padding-right: 10px;
            }
            p {
                font-weight: 500;
            }
        </style>
        <h2 class="text-center mb-3 mt-3">HÓA ĐƠN BÁN HÀNG</h2>
        <p class="text-end" style="padding-right: 10px;">Ngày ${day} tháng ${month} năm ${year}</p>
        <div class="card">
            <div class="card-body">
                <p>Thông tin người bán</p>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th scope="col">Tên công ty</th>
                            <th scope="col">Mã số thuế</th>
                            <th scope="col">Email</th>
                            <th scope="col">Địa chỉ</th>
                            <th scope="col">Số điện thoại</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>47Shop JSC</td>
                            <td>999999999</td>
                            <td>admin@gmail.com</td>
                            <td>Q8, TP.HCM</td>
                            <td>0123456789</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        ${document.getElementById('print-section').innerHTML}
    `;

    // Lưu nội dung gốc
    const originalContents = document.body.innerHTML;

    // Gán nội dung mới để in
    document.body.innerHTML = printContents;

    // Thực hiện in
    window.print();

    // Phục hồi nội dung ban đầu
    document.body.innerHTML = originalContents;
    window.location.reload();
});


