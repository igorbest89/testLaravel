<?php


namespace App\Http\Controllers;

use App\Customer;
use App\Event;
use App\EventVisitiors;
use Illuminate\Http\Request;

class FormController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $event = Event::all();
        return view('form.index', ['events' => $event]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $customers = Customer::getRemoteUsers();

        return view('form.create', ['customers' => $customers, 'event' => $request->request->get('event')]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $visitorIds = [];
        //if isset currently customer, we should be get customer id
        // and push to visitors ids array, else create new Customer
        // and push customer id to array
        if ($request->request->get('visitor') !== null) {
            foreach ($request->request->get('visitor') as $visitor) {
                if (Customer::where('name', $visitor['name'])->get()->first() !== null) {
                    $customer = Customer::where('name', $visitor['name'])->get()->first();
                } else {
                    $customer = new Customer(['name' => $visitor['name'], 'phone' => $visitor['phone']]);
                    $customer->save();
                }
                $visitorIds[] = $customer->id;
            }
            $customer = Customer::find($visitorIds[0]);
            $customer->checkCountry();

            EventVisitiors::setVisit($visitorIds, $request->request->get('event'));

            return redirect()->route('form.index');
        }

        return redirect()->route('form.create');
    }


    /**
     * Autocomplete method for form page
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function search(Request $request)
    {
        $search = $request->get('term');
        $customers = Customer::getRemoteUsers();

        $result = [];

        if (!empty($customers)) {
            foreach ($customers->customers as $customer) {
                if (stristr($customer->first_name, $search) !== false) {
                    $result[] = ['phone' => $customer->phone, 'name' => $customer->first_name];
                };
            }
        }

        return response()->json($result);
    }
}
