sudo dnf update && sudo dnf upgrade

dconf load / < ~/Documents/dotfiles/dconf-backup.txt
sudo dnf install \
  https://download1.rpmfusion.org/free/fedora/rpmfusion-free-release-$(rpm -E %fedora).noarch.rpm
sudo dnf install \
  https://download1.rpmfusion.org/nonfree/fedora/rpmfusion-nonfree-release-$(rpm -E %fedora).noarch.rpm
flatpak remote-add --if-not-exists flathub https://flathub.org/repo/flathub.flatpakrepo

echo "max_parallel_downloads=10" >> /etc/dnf/dnf.conf
echo "fastestmirror=true" >> /etc/dnf/dnf.conf
