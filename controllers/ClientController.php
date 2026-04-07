<?php
class ClientController
{
    /**
     * Hiển thị trang chủ client
     */
    public function index()
    {
        view('client/index', [
            'title' => 'Trang Chủ - Travel Manager',
            'page' => 'client/index'
        ]);
    }

    /**
     * Hiển thị trang About
     */
    public function about()
    {
        view('client/about', [
            'title' => 'Về Chúng Tôi - Travel Manager',
            'page' => 'client/about'
        ]);
    }

    /**
     * Hiển thị trang Tour
     */
    public function tour()
    {
        view('client/tour', [
            'title' => 'Tour - Travel Manager',
            'page' => 'client/tour'
        ]);
    }

    /**
     * Hiển thị trang Hotel
     */
    public function hotel()
    {
        view('client/hotel', [
            'title' => 'Khách Sạn - Travel Manager',
            'page' => 'client/hotel'
        ]);
    }

    /**
     * Hiển thị trang Contact
     */
    public function contact()
    {
        view('client/contact', [
            'title' => 'Liên Hệ - Travel Manager',
            'page' => 'client/contact'
        ]);
    }

    /**
     * Hiển thị trang Hotel Single
     */
    public function hotelSingle()
    {
        view('client/hotel-single', [
            'title' => 'Chi Tiết Khách Sạn - Travel Manager',
            'page' => 'client/hotel-single'
        ]);
    }
}
?>
