<?php
namespace App\Http\Controllers\FrontEnd;
use App\About;
use App\Banner;
use App\Blog;
use App\Clientfeedback;
use App\Deliverycharge;
use App\Faq;
use App\Feature;
use App\Gallery;
use App\Http\Controllers\Controller;
use App\Http\Requests\ContactFormRequest;
use App\Mail\ContactUsFormSubmitMail;
use App\Merchantcharge;
use App\Nearestzone;
use App\Notice;
use App\Parcelnote;
use App\Partner;
use App\Price;
use App\Service;
use App\Branch;
use Brian2694\Toastr\Facades\Toastr;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use PhpParser\Node\Expr\Array_;
use Session;
use App\ParcelTypeDescribe;
use App\Parcel;

class FrontEndController extends Controller {
    public function index() {
        $banner          = Banner::where('status', 1)->orderBy('id', 'DESC')->get();
        $partners        = Partner::where('status', 1)->orderBy('id', 'DESC')->get();
        $about           = About::where('status', 1)->limit(1)->orderBy('id', 'DESC')->get();
        $prices          = Price::where('status', 1)->limit(9)->orderBy('id', 'ASC')->get();
        $features        = Feature::where('status', 1)->orderBy('id', 'ASC')->get();
        $clientsfeedback = Clientfeedback::where('status', 1)->orderBy('id', 'DESC')->get();
        $blogs           = Blog::where('status', 1)->limit(3)->orderBy('id', 'DESC')->get();
        $frnservices     = Service::where('status', 1)->limit(6)->orderBy('id', 'ASC')->get();
        $globNotice = Notice::where('published', 1)->first();
        return view('frontEnd.index', compact('banner', 'about', 'prices', 'features', 'clientsfeedback', 'blogs', 'frnservices', 'partners', 'globNotice'));
    }

    public function login() {
        return view('backEnd.setting.login');
    }
    public function invoice($id)
    {
        $show_data = \App\Parcel::select('*')
            ->with(['pickupcity', 'deliverycity', 'pickuptown', 'deliverytown', 'merchant', 'deliverymen', 'agent', 'parceltype'])
            ->where('id', $id)
            ->first();

        if ($show_data != null) {
            return view('frontEnd.layouts.pages.agent.invoice', compact('show_data'));
        } else {
            Toastr::error('Opps!', 'Your process wrong');

            return redirect()->back();
        }

    }
    public function webinvoice($id)
    {
        $show_data = \App\Parcel::select('*')
            ->with(['pickupcity', 'deliverycity', 'pickuptown', 'deliverytown', 'merchant', 'deliverymen', 'agent', 'parceltype'])
            ->where('id', $id)
            ->first();

        if ($show_data != null) {
            return view('frontEnd.layouts.pages.invoice', compact('show_data'));
        } else {
            Toastr::error('Opps!', 'Your process wrong'); 

            return redirect()->back();
        }
    }
    public function register() {

        return view('frontEnd.layouts.pages.register');
    }

    public function marchentlogin() {

        if (Session::get('merchantId')) {
            return redirect('merchant/dashboard');
        } else {
            return view('frontEnd.layouts.pages.marchentlogin');
        }

    }

    public function parceltrack(Request $request) {

        $trackparcel = Parcel::where('trackingCode', $request->trackparcel)->with('pickupcity', 'deliverycity', 'pickuptown', 'deliverytown', 'merchant', 'deliverymen', 'parceltype')->first();
      
        if ($trackparcel) {
            $trackInfos = Parcelnote::where('parcelId', $trackparcel->id)->orderBy('id', 'ASC')->get();

            return view('frontEnd.layouts.pages.trackparcel', compact('trackparcel', 'trackInfos'));
        } else {
            return redirect()->back();
        }

    }

    public function parceltrackget($id) {
        $trackparcel = DB::table('parcels')
            ->join('nearestzones', 'parcels.reciveZone', '=', 'nearestzones.id')
            ->where('parcels.trackingCode', $id)
            ->select('parcels.*', 'nearestzones.zonename')
            ->orderBy('id', 'DESC')
            ->first();

        if ($trackparcel) {
            $trackInfos = Parcelnote::where('parcelId', $trackparcel->id)->orderBy('id', 'ASC')->get();
            // return $trackInfos;

            return view('frontEnd.layouts.pages.trackparcel', compact('trackparcel', 'trackInfos'));
        } else {
            return redirect()->back();
        }

    }

    public function aboutus() {
        $aboutus = About::where('status', 1)->limit(1)->orderBy('id', 'DESC')->get();

        return view('frontEnd.layouts.pages.aboutus', compact('aboutus'));
    }

    public function ourservice($id) {
        $servicedetails = Service::where(['id' => $id, 'status' => 1])->first();

        if ($servicedetails) {
            return view('frontEnd.layouts.pages.service', compact('servicedetails'));
        } else {
            return redirect('404');
        }

    }

    public function blog() {
        $blogs = Blog::where('status', 1)->simplePaginate(12);

        return view('frontEnd.layouts.pages.blog', compact('blogs'));
    }

    public function blogdetails($id) {
        $blogdetails = Blog::where(['status' => 1, 'id' => $id])->first();

        if ($blogdetails) {
            return view('frontEnd.layouts.pages.blogdetails', compact('blogdetails'));
        } else {
            return redirect()->back();
        }

    }

    public function price() {
        $prices = Price::where('status', 1)->orderBy('id', 'ASC')->get();

        return view('frontEnd.layouts.pages.price', compact('prices'));
    }

    public function features() {
        $features = Feature::where('status', 1)->orderBy('id', 'ASC')->get();

        return view('frontEnd.layouts.pages.features', compact('features'));
    }

    public function featuredetails($id) {
        $feature = Feature::where(['status' => 1, 'id' => $id])->first();

        if ($feature) {
            return view('frontEnd.layouts.pages.featuredetails', compact('feature'));
        } else {
            return redirect()->back();
        }

    }

    public function contact() {
        return view('frontEnd.layouts.pages.contact');
    }

    public function contactFormValidate(ContactFormRequest $request)
    {
        print_r($_POST); die;
        return response()->json(['status' => 200]);
    }
    public function contactSubmit(ContactFormRequest $request) {

        try {

            $contact = new Array_();
            $contact->first_name = $request->first_name;
            $contact->last_name = $request->last_name;
            $contact->email = $request->email;
            $contact->phone = $request->phone;
            $contact->subject = $request->subject;
            $contact->message = $request->message;

            Mail::to(['e-tailing@zidrop.com'])->send(new ContactUsFormSubmitMail($contact));

        } catch (\Exception $exception) {
            Log::info($request->all());
            Log::info('Contact Us page mail error. -- '.$exception->getMessage());
        }

        Toastr::success('Email sent!', '');
        return redirect()->back();
    }

    public function termscondition() {
        return view('frontEnd.layouts.pages.termscondition');
    }

    public function faq() {
        return view('frontEnd.layouts.pages.faq');
    }

    public function notice() {
        $notices = Notice::where('status', 1)->orderBy('id', 'DESC')->get();

        return view('frontEnd.layouts.pages.notice', compact('notices'));
    }

    public function noticedetails($id, $slug) {
        $noticedetails = Notice::where(['id' => $id, 'status' => 1])->first();

        if ($noticedetails) {
            return view('frontEnd.layouts.pages.noticedetails', compact('noticedetails'));
        } else {
            return redirect('404');
        }

    }
    public function branches() {
        $branches = Branch::where('is_active', 1)->orderBy('serial', 'asc')->with('city', 'town')->simplePaginate(12);

        return view('frontEnd.layouts.pages.branches', compact('branches'));
    }
    public function subscriptions() {
        $branches = Branch::where('is_active', 1)->orderBy('serial', 'asc')->with('city', 'town')->simplePaginate(12);

        return view('frontEnd.layouts.pages.subscriptions', compact('branches'));
    }
    public function gallery() {
        $galleries = Gallery::where('status', 1)->orderBy('id', 'DESC')->get();

        return view('frontEnd.layouts.pages.gallery', compact('galleries'));
    }

    public function pickndrop() {
        $state = Deliverycharge::all();
        return view('frontEnd.layouts.pages.pickndrop', compact('state'));
    }

    public function branch() {
        // $galleries = Gallery::where('status',1)->orderBy('id','DESC')->get();
        return view('frontEnd.layouts.pages.branch');
    }

    public function privacy() {
        return view('frontEnd.layouts.pages.privacy');
    }

    public function delivryCharge($id) {
        
        $charge = Merchantcharge::where(['merchantId' => Session::get('merchantId'), 'packageId' => $id])->first();
  
        // Clear old session
        Session::forget('deliverycharge');
        Session::forget('exdeliverycharge');
        Session::forget('codcharge');
        // Session::forget('codpay');
        // Session::forget('pdeliverycharge');
        // Session::forget('pcodecharge');
        // Session::forget('mtax');
        // Session::forget('minsurance');
        
        if ($charge->delivery != 0 && $charge->extradelivery != 0 && $charge->cod != 0) {
            // dd($charge);
            Session::put('deliverycharge', $charge->delivery);
            Session::put('exdeliverycharge', $charge->extradelivery);
            Session::put('codcharge', $charge->cod);
        } else {
            // dd($charge);
            $charge = Deliverycharge::where(['id' => $id])->first();
            Session::put('deliverycharge', $charge->deliverycharge);
            Session::put('exdeliverycharge', $charge->extradeliverycharge);
            Session::put('codcharge', $charge->cod);
        }
        
        return response()->json(compact('charge'));
    }
    
    public function costCalculate($packageid, $cod, $weight, $reciveZone) {
        $codtype = Deliverycharge::where(['id' => $packageid])->first();
        $area    = Nearestzone::find($reciveZone);
        $weight  = $weight ? $weight : 1;
        if ($weight > 1) {
            $extraweight    = $weight - 1;
            $deliverycharge = (Session::get('deliverycharge') + $area->extradeliverycharge) + ($extraweight * Session::get('exdeliverycharge'));
        } else {
            $deliverycharge = (Session::get('deliverycharge') + $area->extradeliverycharge);
        }

        $codcharge = ($cod * $codtype->cod) / 100;
        // Tax Calculation And Insurance calculation
        $tax = $deliverycharge * $codtype->tax / 100;
        $tax = round($tax, 2);
        $insurance = $cod ? $cod * $codtype->insurance / 100 : 0;
        $insurance = round($insurance, 2);

        Session::put('codpay', $cod);
        Session::put('pdeliverycharge', $deliverycharge);
        Session::put('pcodecharge', $codcharge);
        Session::put('mtax', $tax);
        Session::put('minsurance', $insurance);

        return response()->json($deliverycharge);

    }

    public function costCalculateResult() {
        return view('frontEnd.layouts.pages.costcalculate');
    }

    public function get_area($id) {
        $charge = Merchantcharge::where(['merchantId' => Session::get('merchantId'), 'packageId' => $id])->first();
        if ( $charge && $charge->delivery != 0 ) {
            $charge->deliverycharge = $charge->delivery;
            $charge->extradeliverycharge = $charge->extradelivery;
            $charge->tax = 7.5;
            $charge->insurance = 2;
            $charge = $charge;
        } else {
            $charge = Deliverycharge::where(['id' => $id])->first();
        }


        $area = Nearestzone::where('state',$id)->where('status',1)->get();

        return response()->json(compact('area','charge'));
    }
      public function understanding_tracking_status() {
        $parcelTypeDescribes = ParcelTypeDescribe::with('parcelType')->where('status', 1)->get();
        return view('frontEnd.layouts.pages.understanding_tracking_status', compact('parcelTypeDescribes'));
    }

}
