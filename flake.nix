{
  inputs = {
    nixpkgs.url = "github:NixOS/nixpkgs/release-23.05";
    flake-utils.url = "github:numtide/flake-utils";
  };

  outputs = { nixpkgs, flake-utils, ... }:
    flake-utils.lib.eachDefaultSystem (system:
      let
        pkgs = import nixpkgs {
          inherit system;

          config.permittedInsecurePackages = [
            "openssl-1.1.1v" # for php80
          ];
        };

        php = pkgs.php80.withExtensions ({ enabled, all }: with all; enabled ++ [ redis pgsql ]);
      in
      {
        formatter = nixpkgs.legacyPackages.${system}.nixpkgs-fmt;

        devShells.default = pkgs.mkShell {
          buildInputs = [
            pkgs.docker
            pkgs.git
            php
            php.packages.composer
          ];
        };
      }
    );
}
