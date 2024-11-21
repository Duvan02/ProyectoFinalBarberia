using BarberApp.Movil.Models;
using CommunityToolkit.Mvvm.ComponentModel;
using CommunityToolkit.Mvvm.Input;

namespace BarberApp.Movil.ViewModels;
public partial class DetailServiceViewModel : ObservableObject, IQueryAttributable
{
    [ObservableProperty]
    private Servicio _servicio;
    public void ApplyQueryAttributes(IDictionary<string, object> query)
    {
        Servicio = query["Servicio"] as Servicio;
    }

    [RelayCommand]
    public async Task GoToReserva()
    {
        var navigationParameter = new ShellNavigationQueryParameters
        {
            {"Servicio", Servicio }
        };
        await Shell.Current.GoToAsync("addReservation", navigationParameter);
    }
}
