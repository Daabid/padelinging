<?php
    namespace App\Http\Controllers;
    use App\Models\Reserva;
    use App\Models\Pista;
    use Illuminate\Http\Request;
    use App\Http\Controllers\Controller;

    class ReservaController extends Controller
    {
        public function index()
        {
            $reservas = Reserva::all(); // Obtenemos datos con el modelo
            return view('calendario', compact('reservas'));
        }

/**
 * @reservasSemanal
 */
        public function reservasSemanal(Request $request)
        {
            $operturapadeling = $request->fecha . ' ' . '08:00:00';

            $horaInicial = new \DateTime($operturapadeling);

            $pistasHorarios = []; // Guardaremos el horario de un dia de cada pista en este array
            $pistas = Pista::all('IDPista'); // Cojemos todos los id's de las pistas

            foreach($pistas as $pista)
            {
                $calendario = [];
                for($cont2 = 0;$cont2<13;$cont2++)
                {
                    $reserva = Reserva::where('FInicio', '<=', $horaInicial)
                        ->where('FFinal', '>', $horaInicial)
                        ->where('Pista', '=', $pista['IDPista'])
                        ->get();
                    $calendario[] = ['estado' => $reserva->isEmpty() ? 'Libre' : 'Reservado', 'fecha_hora' => $horaInicial->format('Y-m-d H:i'),];
                    $horaInicial->modify('+1 hours');
                }
                $horaInicial->modify('-13 hours');
                $pistasHorarios[$pista['IDPista']] = $calendario;
            }

            return response()->json($pistasHorarios);
            //return view('calendario', compact('calendario'));
        } 
    }
?>