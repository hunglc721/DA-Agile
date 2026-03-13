<?php
/**
 * Report Controller
 * Tạo báo cáo cho hệ thống
 */

class ReportController
{
    /**
     * Báo cáo vận hành tour - Doanh thu, Chi phí, Lợi nhuận
     */
    public function tourFinancialReport()
    {
        try {
            $reportModel = new Report();
            $categoryModel = new TourCategory();
            $tourTypeModel = new TourType();

            // Lấy filters
            $filters = [
                'category_id' => $_GET['category_id'] ?? null,
                'tour_type' => $_GET['tour_type'] ?? null,
                'from_date' => $_GET['from_date'] ?? null,
                'to_date' => $_GET['to_date'] ?? null,
            ];

            // Lấy dữ liệu báo cáo
            $tours = $reportModel->getTourFinancialReport($filters);
            $summary = $reportModel->getReportSummary($filters);

            // Lấy danh mục và loại tour cho filters
            $categories = $categoryModel->findAll();
            $tourTypes = $tourTypeModel->findAll();

            $data = [
                'title' => 'Báo Cáo Vận Hành Tour',
                'page' => 'reports',
                'content_view' => 'admin/tour_financial_report',
                'tours' => $tours,
                'summary' => $summary,
                'categories' => $categories,
                'tourTypes' => $tourTypes,
                'filters' => $filters
            ];

            view('main', $data);
        } catch (Exception $e) {
            error_log("ERROR in ReportController: " . $e->getMessage());
            $_SESSION['error'] = 'Lỗi: ' . $e->getMessage();
            redirect('dashboard');
        }
    }

    /**
     * Export báo cáo dưới dạng CSV
     */
    public function exportCSV()
    {
        try {
            $reportModel = new Report();

            $filters = [
                'category_id' => $_GET['category_id'] ?? null,
                'tour_type' => $_GET['tour_type'] ?? null,
                'from_date' => $_GET['from_date'] ?? null,
                'to_date' => $_GET['to_date'] ?? null,
            ];

            $tours = $reportModel->getTourFinancialReport($filters);
            $summary = $reportModel->getReportSummary($filters);

            // Tạo CSV
            header('Content-Type: text/csv; charset=utf-8');
            header('Content-Disposition: attachment; filename=tour_financial_report_' . date('Y-m-d') . '.csv');

            $output = fopen('php://output', 'w');
            fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF)); // UTF-8 BOM

            // Header
            fputcsv($output, ['Báo Cáo Tài Chính Tour - ' . date('d/m/Y H:i:s')], ',');
            fputcsv($output, [], ',');

            // Summary
            fputcsv($output, ['Tổng Hợp'], ',');
            fputcsv($output, [
                'Tổng Tour',
                'Tổng Đặt Tour',
                'Đã Xác Nhận',
                'Doanh Thu (VNĐ)',
                'Chi Phí (VNĐ)',
                'Lợi Nhuận (VNĐ)'
            ], ',');
            fputcsv($output, [
                $summary['total_tours'] ?? 0,
                $summary['total_bookings'] ?? 0,
                $summary['confirmed_bookings'] ?? 0,
                $summary['total_revenue'] ?? 0,
                $summary['total_cost'] ?? 0,
                $summary['total_profit'] ?? 0
            ], ',');

            fputcsv($output, [], ',');

            // Chi tiết tour
            fputcsv($output, ['Chi Tiết Theo Tour'], ',');
            fputcsv($output, [
                'ID Tour',
                'Mã Tour',
                'Tên Tour',
                'Danh Mục',
                'Loại Tour',
                'Giá/Người',
                'Chi Phí/Người',
                'Số Đặt',
                'Đã Xác Nhận',
                'Doanh Thu (VNĐ)',
                'Chi Phí (VNĐ)',
                'Lợi Nhuận (VNĐ)'
            ], ',');

            foreach ($tours as $tour) {
                fputcsv($output, [
                    $tour['id'],
                    $tour['tour_code'] ?? 'N/A',
                    $tour['tour_name'],
                    $tour['category_name'] ?? 'N/A',
                    $tour['tour_type'],
                    number_format($tour['unit_price'], 0, ',', '.'),
                    number_format($tour['unit_cost'], 0, ',', '.'),
                    $tour['total_bookings'] ?? 0,
                    $tour['confirmed_bookings'] ?? 0,
                    number_format($tour['revenue'] ?? 0, 0, ',', '.'),
                    number_format($tour['total_cost'] ?? 0, 0, ',', '.'),
                    number_format($tour['profit'] ?? 0, 0, ',', '.')
                ], ',');
            }

            fclose($output);
            exit;
        } catch (Exception $e) {
            error_log("ERROR in ReportController exportCSV: " . $e->getMessage());
            $_SESSION['error'] = 'Lỗi xuất báo cáo: ' . $e->getMessage();
            redirect('tour_financial_report');
        }
    }
}
