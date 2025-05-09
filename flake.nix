{
  description = "Laravel Development Environment";

  inputs = {
    nixpkgs.url = "github:NixOS/nixpkgs/nixos-24.11";
  };

  outputs = {
    self,
    nixpkgs,
  }: let
    system = "x86_64-linux";
    pkgs = import nixpkgs {inherit system;};
    phpWithExt = pkgs.php84.buildEnv {
      extensions = (
        {
          enabled,
          all,
        }:
          enabled
          ++ (with all; [all.xdebug])
      );
      extraConfig = ''
        xdebug.mode=develop,debug,profile,trace
        xdebug.start_with_request=trigger
      '';
    };
  in {
    devShells.${system}.default = pkgs.mkShell {
      packages = with pkgs; [
        # PHP
        phpWithExt
        phpWithExt.packages.composer

        # Node
        nodejs_23

        # Tools
        mailpit
      ];

      shellHook = ''
        export COMPOSER_HOME="$PWD/.dev-shell";
         export PATH="$PWD/.dev-shell/vendor/bin:$PATH"
      '';
    };
  };
}
