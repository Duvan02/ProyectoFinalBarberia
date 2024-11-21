using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace BarberApp.Movil.Models;
public class Reserva
{
    public DateTime FechaCreacionReserva { get; set; }
    public int IdUsuario { get; set; }
    public int IdEstilista { get; set; }
    public int IdServicio { get; set; }
    public DateTime FechaReserva { get; set; }
    public TimeSpan HoraInicio { get; set; }
    public TimeSpan HoraFin { get; set; }
}
