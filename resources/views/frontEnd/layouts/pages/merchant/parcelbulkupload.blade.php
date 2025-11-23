@extends('frontEnd.layouts.pages.merchant.merchantmaster')
@section('title','Bulk Upload')
@section('content')
<style>
.tabulator-col-sorter{
  display: none !important;
}
.btn-outline-danger:focus {
box-shadow: none !important;
}
  </style>
@php
    // ▼ replace these queries with whatever you use to fetch titles ▼
    $wcities      = $wcities->pluck('title','id');   // [id => "Lahore", …]
    $wtowns       = [];    // [id => "DHA",   …]
    $payments     = ['1' => 'Prepaid', '2' => 'Pay on Delivery'];
    $parcelTypes  = ['1' => 'Regular','2' => 'Liquid','3' => 'Fragile'];
@endphp

{{-- assets --}}
<link  rel="stylesheet" href="https://unpkg.com/tabulator-tables@5.6.1/dist/css/tabulator.min.css">
<script src="https://unpkg.com/tabulator-tables@5.6.1/dist/js/tabulator.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.19.3/xlsx.full.min.js"></script>


{{-- push option maps to JS --}}
<script>
const CITY_OPTIONS    = @json($wcities);     // { "5":"Lahore", … }
const TOWN_OPTIONS    = @json($wtowns);      // { "17":"DHA",   … }
const PAYMENT_OPTIONS = @json($payments);    // { "1":"Prepaid", … }
const PARCEL_TYPES    = @json($parcelTypes); // { "1":"Regular", … }
</script>

<form id="bulkForm" action="{{ url('merchant/parcel/bulk-import') }}" method="POST">
@csrf

<div class="my-4 d-flex justify-content-between align-items-center">
    <h5 class="mb-0"><i class="fas fa-file-upload"></i> Bulk Parcel Upload</h5>

    <div>
        <button id="btnImport" type="button" class="btn btn-danger">
            <i class="fas fa-file-upload"></i> Import CSV
        </button>
        
        <a href="{{ url('merchant/parcel/template') }}" class="btn btn-outline-danger">
            <i class="fas fa-download"></i> Download Sample CSV
        </a>
        <input id="csvChooser" type="file" accept=".csv,.xlsx,.xls" class="d-none">
    </div>
</div>

<div class="card-body p-0">
    <div id="csvEditor" style="height:420px;"></div>
</div>

<input type="hidden" name="payload" id="payload">

<button class="btn btn-danger mt-3">
     Submit
</button>
<button id="btnClear" type="button" class="btn btn-outline-danger mt-3">
    Clear Table
</button>
</form>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
/* ===========================================================
   GLOBAL HELPERS & STATE
================================================================ */
let table;                      // Tabulator instance
const TOWN_CACHE = {};          // per‑city cache

/* show label instead of id in dropdown cells */
const makeDropdownFormatter = lookup => cell => `
  <span class="d-flex align-items-center">
    <span class="me-1">${lookup[cell.getValue()] || ''}</span>
    <i class="fas fa-caret-down" style="color:#EEEEEE"></i>
  </span>`;

/* AJAX towns list (cached) */
function fetchTowns(cityId){
  if(!cityId)                return Promise.resolve({});
  if(TOWN_CACHE[cityId])     return Promise.resolve(TOWN_CACHE[cityId]);

  return fetch(`{{ url('/merchant/get-town') }}/${cityId}`)
    .then(r=>r.json())
    .then(d=>{
      const map = Array.isArray(d)
                 ? Object.fromEntries(d.map(t=>[t.id,t.title]))
                 : d;
      return (TOWN_CACHE[cityId]=map);
    });
}

/* replace the town dropdown in a single row */
async function refreshTownForRow(row,isPickup){
  const d   = row.getData();
  const cid = isPickup ? d.PickupCity : d.DeliveryCity;
  const fld = isPickup ? 'PickupTown' : 'DeliveryTown';
  const opt = await fetchTowns(cid);

  row.update({[fld]:''});
  const col = row.getCell(fld).getColumn();
  col.updateDefinition({editorParams:{values:opt},
                        formatter:makeDropdownFormatter(opt)});
  row.getCell(fld).edit(false);
}

/* tiny helpers for padding blank rows */
const headers=[
  'rownum',
  'CustomerName(*)','ProductName(*)',
  'PickupCity','PickupTown',
  'DeliveryCity','DeliveryTown',
  'ParcelType','PhoneNumber(*)',
  'PaymentType','CashCollectionAmount(*)',
  'PackageValue(*)','OrderNumber(*)',
  'ProductColor(*)','Weight(*)','ProductQty(*)',
  'DeliveryAddress(*)','Note(*)'
];
const blankTemplate = Object.fromEntries(headers.map(h=>[h,'']));
function isRowBlank(row){
  return headers.slice(1).every(k => (row[k]===null||row[k]===''));
}
function padBottomBlanks(count){
  const data = table.getData();
  let trailing=0;
  for(let i=data.length-1;i>=0 && isRowBlank(data[i]);i--) trailing++;
  const need = count - trailing;
  if(need>0){
    const blanks = Array.from({length:need},()=>({...blankTemplate}));
    table.addData(blanks,false,"bottom");
  }
}

/* quick style for errors */
document.head.insertAdjacentHTML(
  'beforeend',
  '<style>.cell-error{background:#f8d7da!important}</style>'
);

/* ===========================================================
   DOMContentLoaded
================================================================ */
document.addEventListener('DOMContentLoaded',()=>{

/* ---------- build Tabulator ---------- */
const columns=[
  {title:'#',field:'rownum',formatter:'rownum',width:60,hozAlign:'center',frozen:true},
  ...headers.slice(1).map(h=>{
    switch(h){
      case 'PickupCity':   return {title:'PickupCity(*)',field:h,editor:'select',
                                   editorParams:{values:CITY_OPTIONS},
                                   formatter:makeDropdownFormatter(CITY_OPTIONS),widthGrow:1.5};
      case 'PickupTown':   return {title:'PickupTown(*)',field:h,editor:'select',
                                   editorParams:{values:TOWN_OPTIONS},
                                   formatter:makeDropdownFormatter(TOWN_OPTIONS),widthGrow:1.5};
      case 'DeliveryCity': return {title:'DeliveryCity(*)',field:h,editor:'select',
                                   editorParams:{values:CITY_OPTIONS},
                                   formatter:makeDropdownFormatter(CITY_OPTIONS),widthGrow:1.5};
      case 'DeliveryTown': return {title:'DeliveryTown(*)',field:h,editor:'select',
                                   editorParams:{values:TOWN_OPTIONS},
                                   formatter:makeDropdownFormatter(TOWN_OPTIONS),widthGrow:1.5};
      case 'PaymentType':return {title:'PaymentType(*)',field:h,editor:'select',
                                   editorParams:{values:PAYMENT_OPTIONS},
                                   formatter:makeDropdownFormatter(PAYMENT_OPTIONS),widthGrow:1};
      case 'ParcelType':   return {title:'ParcelType(*)',field:h,editor:'select',
                                   editorParams:{values:PARCEL_TYPES},
                                   formatter:makeDropdownFormatter(PARCEL_TYPES),widthGrow:1};
      default:             return {title:h.replace(/_/g,' '),field:h,editor:'input',widthGrow:2};
    }
  })
];

const startBlanks = Array.from({length:15},()=>({...blankTemplate}));

table = new Tabulator('#csvEditor',{
  height:420, layout:'fitData', reactiveData:true,
  data:startBlanks, columns
});

table.on('cellEdited',c=>{
  if(c.getField()==='PickupCity')   refreshTownForRow(c.getRow(),true);
  if(c.getField()==='DeliveryCity') refreshTownForRow(c.getRow(),false);
});

/* ===========================================================
   IMPORT HANDLER – add rows on TOP, keep blanks below
================================================================ */
document.getElementById('csvChooser').addEventListener('change',e=>{
  const file=e.target.files[0]; if(!file) return;
  const isCSV = file.name.toLowerCase().endsWith('.csv');
  const reader=new FileReader();

  reader.onload=async evt=>{
    /* ---------- parse CSV or XLSX ---------- */
    let rows;
    if(isCSV){
      const csv=evt.target.result;
      rows=[];let cur=[],v='',q=false;
      for(let i=0;i<csv.length;i++){
        const c=csv[i],n=csv[i+1];
        if(c=='"'&&q&&n=='"'){v+='"';i++;continue;}
        if(c=='"'){q=!q;continue;}
        if(c==','&&!q){cur.push(v);v='';continue;}
        if((c=='\n'||c=='\r')&&!q){
          if(c=='\r'&&n=='\n') i++;
          cur.push(v);rows.push(cur);cur=[];v='';continue;
        }
        v+=c;
      }
      if(v!==''||cur.length){cur.push(v);rows.push(cur);}
    }else{
      const wb=XLSX.read(evt.target.result,{type:'array'});
      const ws=wb.Sheets[wb.SheetNames[0]];
      rows=XLSX.utils.sheet_to_json(ws,{header:1,defval:''});
    }
    rows=rows.filter(r=>r.some(c=>c!=='')); if(!rows.length){
      alert('File is empty');return(e.target.value='');}
    rows=rows.slice(1);   // drop header row

    /* ---------- helpers ---------- */
    const CITY_LBL2ID=Object.fromEntries(
      Object.entries(CITY_OPTIONS).map(([id,l])=>[l.trim().toLowerCase(),id])
    );
    const idx={pc:headers.indexOf('PickupCity'),pt:headers.indexOf('PickupTown'),
               dc:headers.indexOf('DeliveryCity'),dt:headers.indexOf('DeliveryTown')};
    const data=[], errors=[];

    /* ---------- build rows ---------- */
    for(let r=0;r<rows.length;r++){
      const row=rows[r];

      const pcLbl=(row[idx.pc]??'').trim(), dcLbl=(row[idx.dc]??'').trim();
      const pcId=CITY_OPTIONS[pcLbl]?pcLbl:CITY_LBL2ID[pcLbl.toLowerCase()];
      const dcId=CITY_OPTIONS[dcLbl]?dcLbl:CITY_LBL2ID[dcLbl.toLowerCase()];
      if(!pcId)errors.push({row:r,field:'PickupCity',msg:`Unknown Pickup City "${pcLbl}"`});
      if(!dcId)errors.push({row:r,field:'DeliveryCity',msg:`Unknown Delivery City "${dcLbl}"`});

      const ptLbl=(row[idx.pt]??'').trim(), dtLbl=(row[idx.dt]??'').trim();
      let ptId='', dtId='';
      if(ptLbl){
        ptId=await (async()=>{const map=await fetchTowns(pcId);
          const rev=Object.fromEntries(Object.entries(map).map(([id,l])=>[l.trim().toLowerCase(),id]));
          return map[ptLbl]?ptLbl:rev[ptLbl.toLowerCase()]||''})();
        if(!ptId)errors.push({row:r,field:'PickupTown',msg:`Unknown Pickup Town "${ptLbl}"`});
      }
      if(dtLbl){
        dtId=await (async()=>{const map=await fetchTowns(dcId);
          const rev=Object.fromEntries(Object.entries(map).map(([id,l])=>[l.trim().toLowerCase(),id]));
          return map[dtLbl]?dtLbl:rev[dtLbl.toLowerCase()]||''})();
        if(!dtId)errors.push({row:r,field:'DeliveryTown',msg:`Unknown Delivery Town "${dtLbl}"`});
      }

      const rowData=Object.fromEntries(
        headers.slice(1).map((h,i)=>{
          let v=row[i]??'';
          if(h==='PickupCity')   v=pcId||v;
          if(h==='DeliveryCity') v=dcId||v;
          if(h==='PickupTown')   v=ptId;
          if(h==='DeliveryTown') v=dtId;
          return [h,v];
        })
      );
      data.push({ ...blankTemplate, ...rowData });   // include blanks for missing cells
    }

    /* ---------- add rows TOP, keep blanks below ---------- */
    table.addData(data,true,"top").then(()=>{
      padBottomBlanks(15);                          // ensure 15 editable blanks
      /* colour invalid cells */
      const rowsComp=table.getRows();
      errors.forEach(e=>{
        rowsComp[e.row]?.getCell(e.field)
                       ?.getElement()
                       .classList.add('cell-error');
      });
    });

    if(errors.length)
      alert(`${errors.length} issue${errors.length>1?'s':''} found – red cells mark them.`);
    e.target.value='';
  };

  isCSV?reader.readAsText(file):reader.readAsArrayBuffer(file);
});

/* =========================================================
   CLEAR, SUBMIT, OPEN FILE
========================================================= */
// document.getElementById('btnClear').addEventListener('click',()=>{
//   table.replaceData(startBlanks);
//   document.getElementById('csvChooser').value='';
// });
document.getElementById('btnClear').addEventListener('click', () => {
  Swal.fire({
    title: 'Reset the sheet?',
    text:  'All imported or typed data will be lost.',
    icon:  'warning',
    showCancelButton: true,
    confirmButtonText: 'Yes, start over',
    cancelButtonText:  'Keep editing',
    reverseButtons: true
  }).then(result => {
    if (result.isConfirmed) {
      /* simplest way to restore the pristine 15‑row state */
      location.reload();          // full refresh
    }
  });
});

// document.getElementById('bulkForm').addEventListener('submit',()=>{
//   document.getElementById('payload').value=
//     JSON.stringify(table.getData());
// });
// document.getElementById('bulkForm').addEventListener('submit', () => {
//   const filledRows = table.getData().filter(r => !isRowBlank(r));
//   document.getElementById('payload').value = JSON.stringify(filledRows);
// });
document.getElementById('bulkForm').addEventListener('submit', () => {
  const filledRows = table.getData().filter(r => !isRowBlank(r));

  const normalizedRows = filledRows.map(row => ({
    percelType: row['ParcelType'],
    name: row['CustomerName(*)'],
    order_number: row['OrderNumber(*)'],
    address: row['DeliveryAddress(*)'],
    phonenumber: row['PhoneNumber(*)'],
    productName: row['ProductName(*)'],
    productQty: parseInt(row['ProductQty(*)'], 10),
    cod: row['CashCollectionAmount(*)'],
    payment_option: row['PaymentType'],
    weight: parseFloat(row['Weight(*)']),
    note: row['Note(*)'],
    PickupTown: row['PickupTown'],
    PickupCity: row['PickupCity'],
    DeliveryCity: row['DeliveryCity'],
    DeliveryTown: row['DeliveryTown'],
    package_value: row['PackageValue(*)'],
    productColor: row['ProductColor(*)'],
    productPrice: row['ProductPrice(*)'] || null,
    invoiceno: row['InvoiceNo'] || null,
  }));

  document.getElementById('payload').value = JSON.stringify(normalizedRows);
});


document.getElementById('btnImport').addEventListener('click',()=>{
  document.getElementById('csvChooser').click();
});

}); /* DOMContentLoaded */
</script>




@endsection
