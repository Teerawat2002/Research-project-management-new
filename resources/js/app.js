import './bootstrap';

// ===== jQuery =====
import $ from 'jquery';
window.$ = window.jQuery = $;

// ===== Select2 (ESM-safe) =====
import select2 from 'select2';
import 'select2/dist/css/select2.css';
select2($);

// ===== Alpine =====
import Alpine from 'alpinejs';
window.Alpine = Alpine;
Alpine.start();

// ===== flatpickr =====
import flatpickr from "flatpickr";
import "flatpickr/dist/flatpickr.css";

document.addEventListener("DOMContentLoaded", function () {

    // ฟังก์ชันสำหรับผูก Flatpickr แบบ Range
    function initDateRange(startSelector, endSelector) {
        const startEl = document.querySelector(startSelector);
        const endEl = document.querySelector(endSelector);

        if (startEl && endEl) {
            // สร้าง Picker ขึ้นมาก่อน โดยยังไม่ผูก onChange ที่เกี่ยวข้องกัน
            const startPicker = flatpickr(startEl, {
                dateFormat: "Y-m-d", // <--- ค่าที่จะถูกส่งไป Backend
                altInput: true, // <--- เปิดใช้งานการแสดงผลแยก
                altFormat: "d-m-Y", // <--- รูปแบบที่โชว์ให้ผู้ใช้เห็น (วัน-เดือน-ปี)
                allowInput: true
            });

            const endPicker = flatpickr(endEl, {
                dateFormat: "Y-m-d", // <--- ค่าที่จะถูกส่งไป Backend
                altInput: true, // <--- เปิดใช้งานการแสดงผลแยก
                altFormat: "d-m-Y", // <--- รูปแบบที่โชว์ให้ผู้ใช้เห็น (วัน-เดือน-ปี)
                allowInput: true
            });

            // หลังจากสร้างเสร็จ ค่อยมาผูก Event ทีหลัง เพื่อกันการวนลูปชนกันตอนเปิด Modal
            startPicker.config.onChange.push(function (selectedDates, dateStr, instance) {
                if (dateStr) {
                    endPicker.set("minDate", dateStr);
                }
            });

            endPicker.config.onChange.push(function (selectedDates, dateStr, instance) {
                if (dateStr) {
                    startPicker.set("maxDate", dateStr);
                }
            });

            return {
                startPicker,
                endPicker
            };
        }
        return null;
    }

    // Flatpickr สำหรับ Modal Create
    initDateRange("#start_date", "#end_date");

    // Flatpickr สำหรับ Modal Edit
    initDateRange("#edit_start_date", "#edit_end_date");


    // Select2
    if (!$.fn.select2) {
        console.error('Select2 not loaded');
        return;
    }

    // select2 ธรรมดา (ไม่ search)
    $('.select2').select2({
        width: '100%',
        placeholder: function () {
            return $(this).data('placeholder');
        },
        // allowClear: true,
        minimumResultsForSearch: Infinity
    });

    // select2 ที่ต้องค้นหา
    $('.select2-search').select2({
        width: '100%',
        placeholder: function () {
            return $(this).data('placeholder');
        },
        // allowClear: true
    });

    // select2 แบบเลือกหลายค่า
    $('.select2-multi').select2({
        width: '100%',
        placeholder: function () {
            return $(this).data('placeholder');
        },
        // allowClear: true
    });
});
