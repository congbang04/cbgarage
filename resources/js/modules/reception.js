import {
    customerSearch,
    receptionStore,
    tableList,
} from "../services/reception-api";
import { showValidationErrors } from "../helpers/validation";
$(document).ready(function () {
    loadOrders();
    initReceptionSearch();
});
$("#fuel-range").on("input", function () {
    $("#fuel-value").text($(this).val() + "%");
});
$(document).on("click", "#btn-customer-search", function () {
    const phone = $("#customer-phone-search").val();
    customerSearch(phone)
        .done(function (res) {
            console.log(res);
            $("input[name='booking_id']").val(res?.booking_id || "");
            $("input[name='name']").val(res.name);
            $("input[name='phone']").val(res.phone);

            renderCars(res.cars);

            // nếu có xe
            if (res.data?.car) {
                const car = res.data.car;

                $("#license_plate").val(car.license_plate);
                $("#vin_number").val(car.vin_number);
                $("#manufacture_year").val(car.manufacture_year);
                $("#color").val(car.color);

                // gán brand
                $("#brand_id").val(car.brand_id).trigger("change");

                // đợi model load xong rồi set
                setTimeout(() => {
                    $("#car_model_id").val(car.car_model_id);
                }, 50);
            }
        })
        .fail(function (xhr) {
            if (xhr.status === 422) {
                showValidationErrors(xhr.responseJSON.errors, form);
            } else {
                Swal.fire({
                    icon: "error",
                    title: "Rất tiếc...",
                    text:
                        xhr.responseJSON.message ??
                        "Có lỗi xảy ra, vui lòng thử lại.",
                    didClose: () => {
                        $(this).prop("disabled", false).html(originalText);
                    },
                });
            }
        });
});
function renderCars(cars) {
    const box = $("#car-list");
    box.empty();
    if (!cars || cars.length === 0) {
        $("#customer-cars").addClass("d-none");
        return;
    }
    cars.forEach((car) => {
        box.append(`
            <a href="#" 
               class="list-group-item list-group-item-action car-item"
               data-car='${JSON.stringify(car)}'>
               
               <div class="d-flex justify-content-between">
                    <strong>${car.license_plate}</strong>
                    <span class="text-muted">${car.brand.name} ${car.car_model.name}</span>
               </div>
               
               <small class="text-muted">
                   ${car.color} • ${car.manufacture_year}
               </small>

            </a>
        `);
    });
    $("#customer-cars").removeClass("d-none");
}
const brands = window.brands || [];
$(document).on("change", "#brand_id", function () {
    let brandId = $(this).val();
    let modelSelect = $("#car_model_id");

    modelSelect.html('<option value="">-- Chọn dòng xe --</option>');

    let selectedBrand = brands.find((b) => b.id == brandId);

    if (selectedBrand) {
        selectedBrand.car_models.forEach((model) => {
            modelSelect.append(
                `<option value="${model.id}">${model.name}</option>`,
            );
        });
    }
});
$(document).on("click", ".car-item", function (e) {
    e.preventDefault();

    const car = $(this).data("car");

    $("#license_plate").val(car.license_plate);
    $("#vin_number").val(car.vin_number);
    $("#manufacture_year").val(car.manufacture_year);
    $("#color").val(car.color);

    $("#brand_id").val(car.brand_id).trigger("change");

    setTimeout(() => {
        $("#car_model_id").val(car.car_model_id).trigger("change");
    }, 50);
});
$(document).on("submit", "#create-reception", function (e) {
    e.preventDefault();

    const form = $(this);
    const btn = form.find("button[type='submit']");

    const originalText = btn.html();
    btn.prop("disabled", true).html("Đang lưu...");

    const formData = form.serialize();

    receptionStore(formData)
        .done(function (res) {
            Swal.fire({
                icon: "success",
                title: "Thành công",
                text: res.message,
            });

            form[0].reset();
        })
        .fail(function (xhr) {
            if (xhr.status === 422) {
                showValidationErrors(xhr.responseJSON.errors, form);
            } else {
                Swal.fire({
                    icon: "error",
                    title: "Lỗi",
                    text: xhr.responseJSON?.message ?? "Có lỗi xảy ra",
                });
            }
        })
        .always(function () {
            btn.prop("disabled", false).html(originalText);
        });
});
function loadOrders(url = window.routes.tableList) {
    const filters = {
        keyword: $('input[name="keyword"]').val(),
        status: $('select[name="status"]').val(),
        date: $('input[name="date"]').val(),
    };
    tableList(url || window.routes.tableList, filters)
        .done(function (res) {
            $("#table-data").html(res);
        })
        .fail(function (xhr) {
            console.log(xhr.responseText);
        });
}

$(document).on("click", ".pagination a", function (e) {
    e.preventDefault();
    let url = $(this).attr("href");
    loadOrders(url);
});
function initReceptionSearch() {
    let debounceTimer;
    $(document).on("input", 'input[name="keyword"]', function () {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => {
            loadOrders();
        }, 500);
    });

    $(document).on(
        "change",
        'select[name="status"], input[name="date"]',
        function () {
            loadOrders();
        },
    );
}
