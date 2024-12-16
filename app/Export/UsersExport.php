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
        // $org_id = array('108','144');
        // $orders = Order::whereIn('organization_id', $org_id)->where('order_status', "Complete")->where('payment_status',1)->pluck('customer_id')->toArray();

        // $orders = array_unique($orders);

        //  $data = [];
        //  $users = AppUser::whereIn('id',$orders)->get();
        //  foreach ($users as $user) {
            
        //     $data[] = [
        //         'name' => $user->name ." " .$user->last_name, // Assuming there's a relation to get user name
        //         'email' => $user->email, // Assuming there's a relation to get user email
        //         'phone' => substr( $user->phone, -9),
                
        //     ];
        // }
        
        $orders = Order::where('event_id', 202)->where('order_status', "Complete")->where('payment_status',1)->get();
        
        $data = []; // Initialize an empty array to hold the rows
        
        foreach ($orders as $order) {
            $user = AppUser::find($order->customer_id);
            $ticket = Ticket::find($order->ticket_id);
            $order_status = "Complete";
            if($order->payment_status == 0)
            {
                $order_status = "Cancel";
            }
            else
            {
                $order_status = "Complete";
            }
            $data[] = [
                'id' => $order->order_id,
                'name' => $user->name, // Assuming there's a relation to get user name
                'email' => $user->email, // Assuming there's a relation to get user email
                'quantity' => $order->quantity,
                'payment' => $order->payment,
                'ticket name' => $ticket->name,
                'mode' => $order->payment_type,
                'order status' => $order_status
            ];
        }
        
        return collect($data); // Return the data as a collection
    }

    public function headings(): array
    {
        return ['Name', 'Email', 'Phone'];
    }
}
