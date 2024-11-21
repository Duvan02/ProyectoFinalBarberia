using BarberApp.Movil.Models;
using CommunityToolkit.Mvvm.ComponentModel;
using CommunityToolkit.Mvvm.Input;
using System.Net.Http.Json;

namespace BarberApp.Movil.ViewModels;
public partial class HomeViewModel : ObservableObject
{
    readonly HttpClient httpClient = new();

    [ObservableProperty]
    private List<Servicio> _servicios;

    [ObservableProperty]
    private List<Barbero> _barberos;

    [ObservableProperty]
    private string _usuario = DatosSesion.NombreUsuario;

    public async Task ObtenerServicios()
    {
        Servicios = await httpClient.GetFromJsonAsync<List<Servicio>>(Settings.Settings.UrlApi + "/servicios.php");
    }
    public async Task ObtenerBarberos()
    {
        Barberos = await httpClient.GetFromJsonAsync<List<Barbero>>(Settings.Settings.UrlApi + "/barberos.php");
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
    public async Task GoToServices()
    {
        await Shell.Current.GoToAsync("//services");
    }
}
