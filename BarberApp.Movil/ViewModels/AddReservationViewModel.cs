using BarberApp.Movil.Models;
using CommunityToolkit.Mvvm.ComponentModel;
using CommunityToolkit.Mvvm.Input;
using System.Net.Http.Json;

namespace BarberApp.Movil.ViewModels;
public partial class AddReservationViewModel : ObservableObject, IQueryAttributable
{
    readonly HttpClient httpClient = new();

    [ObservableProperty]
    private List<Servicio> _servicios;

    [ObservableProperty]
    private List<Barbero> _barberos;

    [ObservableProperty]
    private Barbero _barberoSelected;

    [ObservableProperty]
    private TimeSpan _horaReserva = new(8, 0, 0);

    [ObservableProperty]
    private DateTime _fechaReserva = DateTime.Now;

    private Servicio ServiceDefault;

    partial void OnHoraReservaChanged(TimeSpan oldValue, TimeSpan newValue)
    {
        _ = ObtenerBarberosDisponibles();
    }

    public void ApplyQueryAttributes(IDictionary<string, object> query)
    {
        if (query.ContainsKey("Servicio"))
        {
            ServiceDefault = query["Servicio"] as Servicio;
        }
    }

    public async Task ObtenerServicios()
    {
        Servicios = await httpClient.GetFromJsonAsync<List<Servicio>>(Settings.Settings.UrlApi + "/servicios.php");
        if (Servicios != null && ServiceDefault != null)
        {
            await SelectService(ServiceDefault);
        }
    }
    public async Task ObtenerBarberosDisponibles()
    {
        if (ServiceDefault == null) return;
        var horaFin = HoraReserva.Add(new TimeSpan(0, ServiceDefault.TiempoDuracion, 0));
        var uri = $"/barberos.php/disponibles?fecha={FechaReserva:yyyy-MM-dd}&horaInicio={HoraReserva}&horaFin={horaFin}";
        Barberos = await httpClient.GetFromJsonAsync<List<Barbero>>(Settings.Settings.UrlApi + uri);
        if (Barberos.Count == 0)
        {
            await App.Current.MainPage.DisplayAlert("Aviso", "No hay barberos disponibles", "Ok");
        }
    }

    [RelayCommand]
    public async Task SelectService(Servicio servicio)
    {
        Servicios.ForEach(s => s.IsSelected = false);
        ServiceDefault = Servicios.FirstOrDefault(s => s.IdServicio == servicio.IdServicio);
        ServiceDefault.IsSelected = true;
        await ObtenerBarberosDisponibles();
    }

    [RelayCommand]
    public void SelectBarbero(Barbero barbero)
    {
        Barberos.ForEach(s => s.IsSelected = false);
        BarberoSelected = Barberos.FirstOrDefault(s => s.IdEstilista == barbero.IdEstilista);
        BarberoSelected.IsSelected = true;
    }

    [RelayCommand]
    public async Task SaveReserva()
    {
        bool valido = await ValidarReserva();
        if (!valido) return;
        var reserva = new Reserva
        {
            FechaCreacionReserva = DateTime.Now,
            FechaReserva = FechaReserva,
            HoraInicio = HoraReserva,
            HoraFin = HoraReserva.Add(new TimeSpan(0, ServiceDefault.TiempoDuracion, 0)),
            IdServicio = ServiceDefault.IdServicio,
            IdEstilista = BarberoSelected.IdEstilista,
            IdUsuario = DatosSesion.IdUsuario
        };
        var result = await httpClient.PostAsJsonAsync($"{Settings.Settings.UrlApi}/reservas.php", reserva);
        if (result.IsSuccessStatusCode)
        {
            await App.Current.MainPage.DisplayAlert("Éxito", "La reserva se ha registrado correctamente", "Ok");
            await Shell.Current.Navigation.PopToRootAsync();
        }
    }
    async Task<bool> ValidarReserva()
    {
        if (ServiceDefault == null)
        {
            await App.Current.MainPage.DisplayAlert("Error","Debe seleccionar un servicio","Ok");
            return false;
        }
        if (BarberoSelected == null)
        {
            await App.Current.MainPage.DisplayAlert("Error", "Debe seleccionar un barbero", "Ok");
            return false;
        }
        return true;
    }
}
