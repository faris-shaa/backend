<?php 
namespace App\Export;

use App\Models\AppUser; // Make sure to import your model
use App\Models\Order; // Make sure to import your model
use App\Models\Ticket;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class UsersExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        $orders = Order::where('event_id', 107)->where('order_status', "Complete")->get();
        
        $data = []; // Initialize an empty array to hold the rows
        
        foreach ($orders as $order) {
            $user = AppUser::find($order->customer_id);
            $ticket = Ticket::find($order->ticket_id);
            $data[] = [
                'id' => $order->order_id,
                'name' => $user->name, // Assuming there's a relation to get user name
                'email' => $user->email, // Assuming there's a relation to get user email
                'quantity' => $order->quantity,
                'payment' => $order->payment,
                'ticket name' => $ticket->name,
                'mode' => $order->payment_type,
            ];
        }
        
        return collect($data); // Return the data as a collection
    }

    public function headings(): array
    {
        return ['ID', 'Name', 'Email', 'Quantity', 'Amount'  , 'Ticket Name' , ' Mode' ];
    }
}
