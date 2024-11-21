namespace BarberApp.Movil.Models;
public class ReservaResponse
{
    public int IdReserva { get; set; }
    public string Usuario { get; set; }
    public string Barbero { get; set; }
    public string Servicio { get; set; }
    public TimeSpan HoraInicio { get; set; }
    public TimeSpan HoraFin { get; set; }
    public DateTime FechaReserva { get; set; }
    public DateTime FechaCreacionReserva { get; set; }
    public string Estado { get; set; }
}

