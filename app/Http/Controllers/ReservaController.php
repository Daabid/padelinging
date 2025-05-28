<?php
    namespace App\Http\Controllers;
    use App\Models\Reserva;
    use Illuminate\Http\Request;
    use App\Http\Controllers\Controller;

    class ReservaController extends Controller
    {
        public function index()
        {
            $reservas = Reserva::all(); // Obtenemos datos con el modelo
            return view('calendario', compact('reservas'));
        }

// Que la funcion devuelva una array con las horas ocupadas y las libres poniendo true o false dependiendo si esta ocupada ho no
// Que henvie los datos de dos semanas y hacemos los calculos
        public function reservasSemanal(Request $request)
        {
            $operturapadeling = date('Y-m-d') . ' ' . '08:00:00';

            $horaInicial = new \DateTime($operturapadeling);

            $calendario = [];
            
            for($cont = 0;$cont <14;$cont++)
            {
                for($cont2 = 0;$cont2<13;$cont2++)
                {
                    $reserva = Reserva::where('FInicio', '<=', $horaInicial)
                        ->where('FFinal', '>', $horaInicial)
                        ->get();
                    if(!$reserva->isEmpty())
                    {
                        $calendario[] = 'Reservado';
                    }
                    else
                    {
                        $calendario[] = 'libre';
                    }
                    $horaInicial->modify('+1 hours');
                }
                $horaInicial->modify('-13 hours');
                $horaInicial->modify('+1 day');
            }

            return view('calendario', compact('calendario'));
        }
    }
?>