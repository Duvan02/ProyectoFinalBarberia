using BarberApp.Movil.Models;
using CommunityToolkit.Mvvm.ComponentModel;
using CommunityToolkit.Mvvm.Input;
using Microsoft.Maui.Controls;
using System.Net.Http.Json;

namespace BarberApp.Movil.ViewModels;
public partial class AccountViewModel : ObservableObject
{
    private readonly HttpClient httpClient = new();

    [ObservableProperty]
    private string _nombreUsuario = DatosSesion.NombreUsuario;

    [ObservableProperty]
    private string _telefonoUsuario = DatosSesion.TelefonoUsuario;

    [ObservableProperty]
    private ImageSource _imagenPerfil;

    [ObservableProperty]
    private bool _nombreEnabled;

    [ObservableProperty]
    private bool _telefonoEnabled;

    [ObservableProperty]
    private bool _existeCambios;

    private string imagenPerfilCode;

    public AccountViewModel()
    {
        CargarFotoUsuario();
    }

    public void CargarFotoUsuario()
    {
        if (!string.IsNullOrEmpty(DatosSesion.FotoUsuario))
        {
            byte[] imageBytes = Convert.FromBase64String(DatosSesion.FotoUsuario);
            ImagenPerfil = ImageSource.FromStream(() => new MemoryStream(imageBytes));
        }
        else
        {
            ImagenPerfil = ImageSource.FromResource("BarberApp.Movil.Resources.Images.photo.png");
        }
    }

    [RelayCommand]
    public async Task SelectImagen()
    {
        var fileResult = await FilePicker.PickAsync(new PickOptions
        {
            PickerTitle = "Selecciona una imagen",
            FileTypes = FilePickerFileType.Images
        });

        if (fileResult != null)
        {
            ExisteCambios = true;
            string filePath = fileResult.FullPath;
            byte[] imageBytes = File.ReadAllBytes(filePath);
            imagenPerfilCode = Convert.ToBase64String(imageBytes);

            // Mostrar la imagen en un control de la interfaz
            ImagenPerfil = ImageSource.FromFile(filePath);
        }
    }

    [RelayCommand]
    public async Task ActualizarPerfilUsuario()
    {
        var usuario = new Usuario
        {
            IdUsuario = DatosSesion.IdUsuario,
            Foto = imagenPerfilCode,
            Nombres = NombreUsuario,
            Telefono = TelefonoUsuario,
        };
        var result = await httpClient.PutAsJsonAsync($"{Settings.Settings.UrlApi}/usuarios.php", usuario);
        if (result.IsSuccessStatusCode)
        {
            await App.Current.MainPage.DisplayAlert("Éxito", "Su perfil se ha actualizado correctamente", "Ok");
            ExisteCambios = false;
        }
        else
        {
            await App.Current.MainPage.DisplayAlert("Error", "Ocurrió un error al actualizar su perfil", "Ok");
        }
    }

    [RelayCommand]
    public void HabilitarNombre()
    {
        ExisteCambios = true;
        NombreEnabled = true;
    }

    [RelayCommand]
    public void HabilitarTelefono()
    {
        ExisteCambios = true;
        TelefonoEnabled = true;
    }

    [RelayCommand]
    public async Task CerrarSesion()
    {
        await Shell.Current.GoToAsync("//login");
    }
}
