using BarberApp.Movil.Models;
using CommunityToolkit.Mvvm.ComponentModel;
using CommunityToolkit.Mvvm.Input;
using System.Net.Http.Json;

namespace BarberApp.Movil.ViewModels;
public partial class ServiciosViewModel : ObservableObject
{
    readonly HttpClient httpClient = new();

    [ObservableProperty]
    private List<Servicio> _servicios;

    [ObservableProperty]
    private bool _isBusy;
    public async Task ObtenerServicios()
    {
        IsBusy = true;
        Servicios = await httpClient.GetFromJsonAsync<List<Servicio>>(Settings.Settings.UrlApi + "/servicios.php");
        IsBusy = false;
    }

    [RelayCommand]
    public async Task GoToServiceDetail(Servicio servicio)
    {
        var navigationParameter = new ShellNavigationQueryParameters
        {
            {"Servicio", servicio }
        };
        await Shell.Current.GoToAsync($"serviceDetail", navigationParameter);
    }

    [RelayCommand]
    public async Task Refresh()
    {
        Servicios = [];
        await ObtenerServicios();
    }
}
