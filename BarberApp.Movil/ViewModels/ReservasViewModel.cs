using BarberApp.Movil.Models;
using CommunityToolkit.Mvvm.ComponentModel;
using CommunityToolkit.Mvvm.Input;
using System.Net.Http.Json;

namespace BarberApp.Movil.ViewModels;
public partial class ReservasViewModel : ObservableObject
{
    private readonly HttpClient httpClient = new();

    [ObservableProperty]
    private List<ReservaResponse> _reservas;

    [ObservableProperty]
    private bool _isBusy;

    public async Task ObtenerReservas()
    {
        IsBusy = true;
        Reservas = [];
        Reservas = await httpClient.GetFromJsonAsync<List<ReservaResponse>>($"{Settings.Settings.UrlApi}/reservas.php?idUsuario={DatosSesion.IdUsuario}");
        IsBusy = false;
    }

    [RelayCommand]
    public async Task CancelarReserva(ReservaResponse reserva)
    {
        var result = await httpClient.PutAsync($"{Settings.Settings.UrlApi}/reservas.php?idReserva={reserva.IdReserva}&estado=CANCELADA",null);
        if (result.IsSuccessStatusCode)
        {
            await App.Current.MainPage.DisplayAlert("Éxito","La reserva ha sido cancelada","Ok");
            await ObtenerReservas();
        }
        else
        {
            await App.Current.MainPage.DisplayAlert("Error","Ocurrió un error al cancelar la reserva","Ok");
        }
    }

    [RelayCommand]
    public async Task AgregarReserva()
    {
        await Shell.Current.GoToAsync("addReservation");
    }


    [RelayCommand]
    public async Task Refresh()
    {
        await ObtenerReservas();
    }
}
