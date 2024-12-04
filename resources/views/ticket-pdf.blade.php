<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Electronic Ticket</title>
    <style>
        * {
            font-family: DejaVu Sans !important;
            margin: auto 0;
            padding: 0;
        }

        body {
            font-size: 13px;
            font-family: 'DejaVu Sans', Arial, sans-serif;
            margin: 0;
            margin-top: 50px;
            padding: 0;
            width: 100%;
            background-color: white;
            page-break-inside: avoid;
        }

        .ticket-wrapper {
            display: table; /* Use display: table on the wrapper */
            width: 100%;
            height: 100%;
            text-align: center; /* Optional: Center horizontally */
            vertical-align: middle; /* Optional: Center vertically */
        }

        .ticket {
            max-width: 90%; /* Ensures the ticket doesn't exceed the page width */
            margin: auto; /* Center horizontally and vertically */
            padding: 20px;
            background: white;
            border: 1px solid #ccc;
            border-radius: 10px;
            text-align: left; /* Ensure left-aligned text for content */
        }

        .ticket-header {
            width: 100%;
            margin: 0 auto; /* Centers the header within the ticket */
            margin-bottom: 20px;
            display: table;
        }

        .ticket-header img {
            display: table-cell;
            width: 100px;
            vertical-align: middle;
        }

        .ticket-header .ticket-number {
            display: table-cell;
            text-align: right;
            font-size: 13px;
            vertical-align: middle;
        }

        .ticket-body {
            width: 100%; /* Subtract padding from the total width */
            margin: 0 auto; /* Centers the body within the ticket */
            background: #f1eff3;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .ticket-container {
            padding: 20px;
        }

        .ticket-title {
            text-align: center;
            margin-bottom: 20px;
        }

        .ticket-title h1 {
            font-size: 18px;
            color: #333;
        }

        .content {
            width: 100%;
            display: table; /* Ensures equal heights for child elements */
            margin: 0 auto 20px auto; /* Centers the content */
        }

        .content .event-image,
        .content .qr-code {
            display: table-cell;
            width: 50%; /* Divide evenly */
            padding: 10px;
            text-align: center; /* Center content within the cell */
            vertical-align: middle;
            border-radius: 10px;
        }

        .content .event-image img,
        .content .qr-code img {
            width: 100%;
            height: auto; /* Maintain aspect ratio */
            max-height: 200px;
            border-radius: 10px;
        }

        .details-section {
            margin-top: 20px;
            width: 100%;
            margin: 0 auto; /* Centers the section */
        }

        .details-section .section-title {
            font-size: 15px;
            color: #6a1b9a;
            margin-bottom: 10px;
            text-align: start; /* Center the title */
        }

        .details-table {
            width: 100%; /* Full width for the table */
            margin: 0 auto; /* Centers the table within its container */
            border-spacing: 10px;
            border-collapse: separate;
        }

        .details-table td {
            background: white;
            border: 1px solid #e5e5e5;
            padding: 10px;
            text-align: left;
            font-size: 10px;
            color: black;
            border-radius: 10px;
        }

        .details-table td span {
            font-weight: bold;
            color: #555;
        }

        .terms-condition {
            display: table;
            width: 100%;
            margin: 0 auto; /* Centers the terms section */
            margin-top: 20px;
            page-break-inside: avoid;
        }

        .terms-condition div {
            display: table-cell;
        }

        .terms-condition h3 {
            font-size: 14px;
            color: #333;
            margin-bottom: 10px;
            text-align: start; /* Center the heading */
        }

        .terms-condition ul {
            padding-left: 20px;
        }

        .terms-condition ul li {
            margin-bottom: 5px;
            font-size: 10px;
            color: #555;
        }

        [dir="rtl"] {
            text-align: right;
        }

        [dir="rtl"] ul {
            padding-right: 20px;
        }

        [dir="rtl"] ul li {
            text-align: right;
        }

        [dir="rtl"] ul {
            direction: rtl; /* Set the direction to right-to-left */
            text-align: right; /* Align the text to the right */
            list-style-position: inside; /* Moves bullets inside the text block */
        }

        [dir="rtl"] ul li {
            text-align: right; /* Ensures the text is aligned to the right */
            direction: rtl; /* Ensures proper text flow for Arabic */
        }


        @page {
            size: A4; /* Adjust to Letter or other dimensions if needed */
            margin: 0px;
        }
    </style>
</head>
<body>
<div class="ticket-wrapper">
    <div class="ticket">
        <div class="ticket-header">
            <img src="data:image/png;base64,{{ base64_encode(file_get_contents( public_path("images/admin-logo-welcome.png") ))}}"
                 alt="Logo">
            <div class="ticket-number">
                <span>Ticket Number</span>
                <strong>231557</strong>
            </div>
        </div>
        <div class="ticket-body">
            <div class="ticket-container">
                <div class="ticket-title">
                    <h1>Electronic Ticket</h1>
                </div>
                <div class="content">
                    <div class="event-image">
                        <img
                                src="data:image/png;base64,{{ base64_encode(file_get_contents( "https://ticketby.co/images/upload/image_1733129778.png" ))}}"
                                alt="Event Image">
                    </div>
                    <div class="qr-code">
                        <img src="data:image/png;base64,{{ base64_encode(file_get_contents( "https://eta.st/assets/img/rsp6/initial-barcode.png" ))}}"
                             alt="QR Code">
                    </div>
                </div>

            </div>
        </div>

        <div class="details-section">
            <div class="section-title">Afro House Night</div>
            <table class="details-table">
                <tr>
                    <td>
                        <span>Category</span> | <span dir="rtl">القسم</span>
                        <br><strong>C</strong>
                    </td>
                    <td>
                        <span>Show Start</span> | <span dir="rtl">بداية العرض</span>
                        <br><strong>20:30 Mon, 29 Apr 24</strong>
                    </td>
                    <td>
                        <span>Gate Close</span> | <span dir="rtl">اغلاق البوابات</span>
                        <br><strong>23:00</strong>
                    </td>
                </tr>
                <tr>
                    <td>
                        <span>Entrance</span> | <span dir="rtl">بوابة الدخول</span>
                        <br><strong>C</strong>
                    </td>
                    <td>
                        <span>Row</span> | <span dir="rtl">الصف</span>
                        <br><strong>FF18</strong>
                    </td>
                    <td>
                        <span>Seat</span> | <span dir="rtl">المقعد</span>
                        <br><strong>23</strong>
                    </td>
                </tr>
                <tr>
                    <td>
                        <span>Location</span> | <span dir="rtl">الموقع</span>
                        <br><strong>King Fahd</strong>
                    </td>
                    <td>
                        <span>Price</span> | <span dir="rtl">السعر</span>
                        <br><strong>100.00SAR incl. of 15%VAT</strong>
                    </td>
                    <td>
                        <span>Age Limit</span> | <span dir="rtl">العمر المحدد</span>
                        <br><strong>15+</strong>
                    </td>
                </tr>
                <tr>
                    <td>
                        <span>Name</span> | <span dir="rtl">الاسم</span>
                        <br><strong>Selim Zahran</strong>
                    </td>
                    <td>
                        <span>Type</span> | <span dir="rtl">نوع التذكرة</span>
                        <br><strong>C</strong>
                    </td>
                    <td>
                        <span>Ticket</span> | <span dir="rtl">رقم التذكرة</span>
                        <br><strong>231557765323456</strong>
                    </td>
                </tr>
            </table>
        </div>
        <div class="terms-condition">
            <div>
                <h3>Terms and Conditions</h3>
                <ul>
                    <li>Agree to adhere to Public Decency Regulations</li>
                    <li>Agree to adhere to venue health and safety rules and regulations</li>
                    <li>Ticket holders may be subject to search and seizure of dangerous
                        or banned objects
                    </li>
                    <li>The event organizer or security detail holds the right to confiscate
                        items deemed dangerous or harmful to the event
                    </li>
                    <li>Ticket holders may be photographed and recorded</li>
                    <li>
                        The event organizer is not responsible for physical injuries,
                        illnesses, or death, or loss or damage of property
                    </li>
                    <li>
                        The event organizer maintains the right to deny event entry or
                        extract persons deemed in violation of the terms and conditions
                    </li>
                </ul>
            </div>
            <div dir="rtl">
                <h3>الشروط والاحكام</h3>
                <ul class="rtl-list">
                    <li>الالتزام بالأنظمة العامة للآداب</li>
                    <li>الالتزام بقواعد الصحة والسلامة في المكان</li>
                    <li>قد يتم تفتيش حاملي التذاكر ومصادرة الأشياء الخطرة أو المحظورة</li>
                    <li>يحق لمنظم الحدث أو الأمن مصادرة الأشياء التي يعتبرونها خطرة أو ضارة بالحدث</li>
                    <li>قد يتم تصوير حاملي التذاكر وتسجيلهم</li>
                    <li>لا يتحمل منظم الحدث المسؤولية عن الإصابات الجسدية أو الأمراض أو الوفاة
                        أو فقدان أو تلف الممتلكات
                    </li>
                    <li>يحافظ منظم الحدث على الحق في رفض دخول الحدث أو إخراج الأشخاص الذين
                        يعتبرون مخالفين للشروط والأحكام
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
</body>
</html>
