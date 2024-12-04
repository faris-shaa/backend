<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Electronic Ticket</title>
    <style>
        * {
            font-family: DejaVu Sans !important;
        }

        body {
            font-size: 16px;
            font-family: 'DejaVu Sans', 'Roboto', 'Montserrat', 'Open Sans', sans-serif;
            padding: 10px;
            margin: 10px;

            color: #777;
        }


        body {
            color: #777;
            text-align: right;
        }

        body h1 {

            margin-bottom: 0px;
            padding-bottom: 0px;
            color: #000;
        }

        body h3 {

            margin-top: 10px;
            margin-bottom: 20px;
            color: #555;
        }

        body a {
            color: #06f;
        }

        @page {
            size: a4;
            margin: 0;
            padding: 0;
        }

        .invoice-box table {
            direction: ltr;
            width: 100%;
            text-align: right;
            border: 1px solid;
            font-family: 'DejaVu Sans', 'Roboto', 'Montserrat', 'Open Sans', sans-serif;
        }


        .row {
            display: block;
            padding-left: 24;
            padding-right: 24;
            page-break-before: avoid;
            page-break-after: avoid;
        }

        .column {
            display: block;
            page-break-before: avoid;
            page-break-after: avoid;
        }

        body {
            font-family: 'Amiri', Arial, sans-serif;
            margin: 0;
            padding: 0;
            width: 100%;
        }

        .ticket {
            width: 800px;
            max-width: 100%;
            margin: auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: none; /* PDF does not support shadows */
        }

        .ticket-body {
            page-break-inside: avoid; /* Prevents splitting the ticket across pages */
        }

        .ticket-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .ticket-header img {
            height: 50px;
        }

        .ticket-header .ticket-number {
            font-size: 14px;
            color: #333;
        }

        .ticket-header .ticket-number span, .ticket-header .ticket-number strong {
            display: block;
            width: 100%;
            text-align: center;
        }


        .ticket-body {
            background-color: #f1eff3;
            padding: 30px;
            border-radius: 20px;
        }

        .ticket-title {
            text-align: center;
            margin-bottom: 20px;
        }

        .ticket-title h1 {
            font-size: 24px;
            margin: 0;
            color: #333;
        }

        .content {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 20px;
            margin-bottom: 20px;
        }

        .event-image, .qr-code {
            width: 45%; /* Both containers have the same width */
            aspect-ratio: 1; /* Ensures both containers are square */
            position: relative;
            border-radius: 10px;
            background-color: #fff;
            overflow: hidden; /* Prevents overflow of image */
        }

        .event-image img, .qr-code img {
            width: 100%;
            height: 100%;
            object-fit: cover; /* Ensures images fill the container without distortion */
            border-radius: 10px;
        }

        .details-section {
            margin-top: 20px;
            font-size: 14px;
            color: #555;
        }

        .details-section .section-title {
            font-size: 25px;
            font-weight: bold;
            color: #6a1b9a;
            margin-bottom: 10px;
        }

        .details-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr); /* Four columns of equal width */
            gap: 10px;
        }

        .details-grid div {
            background: white;
            border: solid #e5e5e5 1px;
            padding: 10px;
            border-radius: 10px;
            text-align: start;
            font-size: 14px;
            color: black;
        }

        .terms-condition {
            display: grid;
            margin-top: 20px;
            page-break-inside: avoid; /* Avoid splitting terms and conditions */
            grid-template-columns: repeat(2, 1fr); /* Four columns of equal width */
            gap: 10px;
        }

        .terms-condition div {
            background: white;
            border: solid #e5e5e5 1px;
            padding: 10px;
            border-radius: 10px;
            text-align: start;
            font-size: 14px;
            color: black;
        }

    </style>
</head>
<body>
<div class="ticket">
    <div class="ticket-header">
        <img src="{{ asset("images/admin-logo-welcome.png") }}" alt="Logo">
        <div class="ticket-number">
            <span>Ticket Number</span>
            <strong>231557</strong>
        </div>
    </div>
    <div class="ticket-body">
        <div class="ticket-title">
            <h1>Electronic Ticket</h1>
        </div>
        <div class="content">
            <div class="event-image">
                <img src="https://ticketby.co/images/upload/6718a1305bb7c.jpeg" alt="Event Image">
            </div>
            <div class="qr-code">
                <img src="{{ asset("qrcodes/qr-2.png") }}" alt="QR Code">
            </div>
        </div>
    </div>

    <div class="details-section">
        <div class="section-title">Afro House Night</div>
        <div class="details-grid">
            <div>
                <span>Category</span>
                |
                <span dir="rtl">القسم</span>
                <br><strong>C</strong>
            </div>
            <div>
                <span>Show Start</span>
                |
                <span dir="rtl">بداية العرض</span>
                <br><strong>20:30 Mon, 29 Apr 24</strong>
            </div>
            <div>
                <span>Gate Close</span>
                |
                <span dir="rtl">اغلاق البوابات</span>
                <br><strong>23:00</strong>
            </div>
            <div>
                <span>Entrance</span>
                |
                <span dir="rtl">بوابة الدخول</span>
                <br><strong>C</strong>
            </div>
            <div>
                <span>Row</span>
                |
                <span dir="rtl">الصف</span>
                <br><strong>FF18</strong>
            </div>
            <div>
                <span>Seat</span>
                |
                <span dir="rtl">المقعد</span>
                <br><strong>23</strong>
            </div>
            <div>
                <span>Location</span>
                |
                <span dir="rtl">الموقع</span>
                <br><strong>King Fahd</strong>
            </div>
            <div>
                <span>Price</span>
                |
                <span dir="rtl">السعر</span>
                <br><strong>100.00SAR incl. of 15%VAT</strong>
            </div>
            <div>
                <span>Age Limit</span>
                |
                <span dir="rtl">العمر المحدد</span>
                <br><strong>15+</strong>
            </div>
            <div>
                <span>Name</span>
                |
                <span dir="rtl">الاسم</span>
                <br><strong>Selim Zahran</strong>
            </div>
            <div>
                <span>Type</span>
                |
                <span dir="rtl">نوع التذكرة</span>
                <br><strong>C</strong>
            </div>
            <div>
                <span>Ticket</span>
                |
                <span dir="rtl">رقم التذكرة</span>
                <br><strong>231557765323456</strong>
            </div>
        </div>
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
            <ul>
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
</body>
</html>
