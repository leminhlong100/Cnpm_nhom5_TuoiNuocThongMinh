// Kiểm tra lỗi dữ liệu khi người dùng nhập vào ở bước 2.6.4
// (bước 2.6.5 ở bên file register.blade.php)
$(document).ready(function () {
    let fullName = "",
        email = "",
        pass = "",
        repass = "",
        birthday = "",
        gender = "",
        phone = "",
        address = "";

    // check họ tên
    $("#input-1").on("input", () => {
        fullName = $("#input-1").val().trim();
        if (
            fullName.length > 0 &&
            (fullName.length < 3 || fullName.length > 50)
        ) {
            let existingErrorMessages = $("#input-1").nextAll(".text-danger");
            if (existingErrorMessages.length == 0) {
                $("#input-1").after(
                    '<small class="form-text text-danger">Họ tên cần lớn hơn 3 kí tự và bé hơn 50 kí tự</small>'
                );
            }
        } else {
            $("#input-1").nextAll(".text-danger").remove();
        }
    });
    // check email xem email này đã được đăng ký trước đó hoặc đã đúng định dạng chưa
    $("#input-2").on("input", () => {
        email = $("#input-2").val().trim();
        if (email.length > 0 && !isValidEmail(email)) {
            // Hiển thị thông báo lỗi định dạng email không hợp lệ
            $("#input-2").nextAll(".text-danger").remove(); // Loại bỏ các thông báo lỗi trước đó
            $("#input-2").after(
                '<small class="form-text text-danger">Email phải có định dạng xx@xx.xx</small>'
            );
        } else {
            $.ajax({
                url: "/checkemail", // phương thức bên trong RegisterController
                type: "GET",
                data: { email: email },
                success: function (data) {
                    if (data.exists) {
                        // Hiển thị thông báo lỗi email đã tồn tại trên hệ thống
                        $("#input-2").nextAll(".text-danger").remove(); // Loại bỏ các thông báo lỗi trước đó
                        $("#input-2").after(
                            '<small class="form-text text-danger">' +
                                data.message +
                                "</small>"
                        );
                    } else {
                        $("#input-2").nextAll(".text-danger").remove(); // Loại bỏ thông báo lỗi nếu có
                    }
                },
            });
        }
    });
    // check mật khẩu
    $("#input-3").on("input", () => {
        pass = $("#input-3").val();
        if (pass.includes(" ")) {
            $("#input-3").nextAll(".text-danger").remove();
            $("#input-3").after(
                '<small class="form-text text-danger">Mật khẩu không được chứa khoảng trắng</small>'
            );
        } else if (pass.trim().length > 0 && pass.trim().length < 8) {
            $("#input-3").nextAll(".text-danger").remove();
            $("#input-3").after(
                '<small class="form-text text-danger">Mật khẩu phải đủ 8 ký tự trở lên</small>'
            );
        } else {
            $("#input-3").nextAll(".text-danger").remove();
        }
    });
    // check nhập lại mật khẩu
    $("#input-4").on("input", () => {
        repass = $("#input-4").val().trim();
        if (pass !== repass && repass.length > 0) {
            let existingErrorMessages = $("#input-4").nextAll(".text-danger");
            if (existingErrorMessages.length == 0) {
                $("#input-4").after(
                    '<small class="form-text text-danger">Mật khẩu xác nhận không khớp</small>'
                );
            }
        } else $("#input-4").nextAll(".text-danger").remove();
    });
    // check ngày sinh phải nhỏ hơn hoặc bằng với ngày hiện tại
    $("#input-5").on("input", () => {
        birthday = $("#input-5").val();
        const today = new Date();
        let errorMessage = "";
        if (new Date(birthday) >= today) {
            errorMessage = "Vui lòng chọn ngày sinh trước ngày hiện tại";
        }

        if (errorMessage !== "") {
            let existingErrorMessages = $("#input-5").nextAll(".text-danger");
            if (existingErrorMessages.length == 0) {
                $("#input-5").after(
                    `<small class="form-text text-danger">${errorMessage}</small>`
                );
            } else {
                existingErrorMessages.text(errorMessage);
            }
        } else {
            $("#input-5").nextAll(".text-danger").remove();
        }
    });
    // check số điện thoại
    $("#input-6").on("input", () => {
        phone = $("#input-6").val();
        // nếu không bắt đầu bằng số 0 và chưa đủ độ dài 10 ký tự, thông báo
        if (!/^[0][0-9]{9}$/.test(phone)) {
            let existingErrorMessages = $("#input-6").nextAll(".text-danger");
            if (existingErrorMessages.length == 0) {
                $("#input-6").after(
                    '<small class="form-text text-danger">Số điện thoại phải đủ 10 số và bắt đầu bằng số 0</small>'
                );
            }
        } else {
            $("#input-6").nextAll(".text-danger").remove();
        }
    });

    // kiểm tra định dạng email
    function isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }

    // Khi click vào nút đăng ký
    $("#register").on("click", () => {
        if (
            fullName === "" ||
            email === "" ||
            pass === "" ||
            repass === "" ||
            phone === "" ||
            $(".column .form-text.text-danger").length > 0
        ) {
            // Nếu thông tin chưa đầy đủ thì hiển thị modal
            $("#modal-check").modal("show");
            // Thiết lập thời gian chờ trước khi tự động đóng modal
            setTimeout(() => {
                $("#modal-check").modal("hide");
            }, 2500);
            return false;
        } else {
            return true;
        }
    });
});
